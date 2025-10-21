<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreSettings;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function storeIdentity()
    {
        // Get or create store settings
        $storeSetting = StoreSettings::firstOrCreate(['id' => 1]);
        
        // Load all images with the store setting
        $storeSetting->load('images');
        
        // Get all home banners (not just single)
        $homeBanners = $storeSetting->images()
            ->where('image_type', 'home_banner')
            ->get();
        
        // Create a store object with all needed data
        $store = (object) [
            'facebook' => $storeSetting->facebook,
            'instagram' => $storeSetting->instagram,
            'linkedin' => $storeSetting->linkedin,
            'youtube' => $storeSetting->youtube,
            'twitter' => $storeSetting->twitter,
            'tiktok' => $storeSetting->tiktok,
            'pinterest' => $storeSetting->pinterest,
            'website_logo' => $storeSetting->websiteLogo() ? $storeSetting->websiteLogo()->image_path : null,
            'favicon' => $storeSetting->favicon() ? $storeSetting->favicon()->image_path : null,
            'footer_logo' => $storeSetting->footerLogo() ? $storeSetting->footerLogo()->image_path : null,
            'home_banners' => $homeBanners, // Changed to collection
        ];
        
        return view('admin.settings.storeIdentity', compact('store'));
    }

    public function updateStoreIdentity(Request $request)
    {
        // Validate the request
        $request->validate([
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'twitter' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_banners.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096', // Multiple banners
        ]);

        // Get or create store settings
        $storeSetting = StoreSettings::firstOrCreate(['id' => 1]);

        // Update social links
        $storeSetting->update([
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
            'twitter' => $request->twitter,
            'tiktok' => $request->tiktok,
            'pinterest' => $request->pinterest,
        ]);

        // Handle single image uploads (website_logo, favicon, footer_logo)
        $singleImageTypes = [
            'website_logo' => 'website_logo',
            'favicon' => 'favicon',
            'footer_logo' => 'footer_logo',
        ];
        
        foreach ($singleImageTypes as $inputName => $imageType) {
            if ($request->hasFile($inputName)) {
                // Find existing image
                $existingImage = $storeSetting->images()
                    ->where('image_type', $imageType)
                    ->first();
                
                // Delete old image file if exists
                if ($existingImage && $existingImage->image_path) {
                    Storage::disk('public')->delete($existingImage->image_path);
                    $existingImage->delete();
                }
                
                // Store new image
                $path = $request->file($inputName)->store('store_images', 'public');
                
                // Create new image record using polymorphic relationship
                $storeSetting->images()->create([
                    'image_path' => $path,
                    'image_type' => $imageType,
                    'is_primary' => 1,
                ]);
            }
        }

        // Handle banner deletions
        if ($request->has('delete_banners')) {
            $deleteBannerIds = array_filter($request->input('delete_banners'));
            
            foreach ($deleteBannerIds as $bannerId) {
                $banner = Image::where('id', $bannerId)
                    ->where('imageable_id', $storeSetting->id)
                    ->where('imageable_type', StoreSettings::class)
                    ->where('image_type', 'home_banner')
                    ->first();
                
                if ($banner) {
                    // Delete file from storage
                    if ($banner->image_path) {
                        Storage::disk('public')->delete($banner->image_path);
                    }
                    $banner->delete();
                }
            }
        }

        // Handle multiple home banner uploads (similar to product images)
        if ($request->hasFile('home_banners')) {
            foreach ($request->file('home_banners') as $bannerFile) {
                $path = $bannerFile->store('store_images/banners', 'public');
                
                // Create new banner record
                $storeSetting->images()->create([
                    'image_path' => $path,
                    'image_type' => 'home_banner',
                    'is_primary' => 0,
                ]);
            }
        }

        return redirect()->route('identity.index')
            ->with('message', 'Store identity updated successfully!');
    }

    // Optional: Add a method to delete individual banners via AJAX
    public function deleteBanner(Request $request)
    {
        $bannerId = $request->input('banner_id');
        
        $banner = Image::where('id', $bannerId)
            ->where('image_type', 'home_banner')
            ->first();
        
        if ($banner) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->delete();
            
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Banner not found'], 404);
    }
}