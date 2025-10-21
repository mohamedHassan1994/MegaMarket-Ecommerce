<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    // Manage Attributes page (ordered by sort_order)
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 25);
        $attributes = Attribute::orderBy('sort_order')->paginate($perPage);
        $attributes->appends($request->query());

        return view('admin.attributes.manage', compact('attributes'));
    }

    // Show create form
    public function create()
    {
        return view('admin.attributes.create');
    }

    // Store new attribute
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:attributes,name',
            'input_type'  => 'required|in:text,select,radio,checkbox',
            'is_required' => 'nullable|boolean',
            'values'      => 'nullable|string',
        ]);

        // normalize boolean
        $validated['is_required'] = $request->has('is_required');

        // extract values string so it's not mass-assigned to Attribute
        $valuesString = $validated['values'] ?? null;
        unset($validated['values']);

        // assign sort_order to the end
        $max = Attribute::max('sort_order') ?? 0;
        $validated['sort_order'] = $max + 1;

        $attribute = Attribute::create($validated);

        // save values if input type supports it
        if (in_array($attribute->input_type, ['select', 'radio', 'checkbox']) && $valuesString) {
            $values = array_map('trim', explode(',', $valuesString));
            foreach ($values as $val) {
                if ($val !== '') {
                    $attribute->values()->create(['value' => $val]);
                }
            }
        }

        return redirect()->route('attributes.index')->with('message', 'Attribute created successfully!');
    }

    // Show edit form
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    // Update attribute
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'input_type'  => 'required|in:text,select,radio,checkbox',
            'is_required' => 'nullable|boolean',
            'values'      => 'nullable|string',
        ]);

        $validated['is_required'] = $request->has('is_required');

        // extract values
        $valuesString = $validated['values'] ?? null;
        unset($validated['values']);

        // update main model
        $attribute->update($validated);

        // sync values depending on input_type
        if (in_array($attribute->input_type, ['select', 'radio', 'checkbox'])) {
            if ($valuesString) {
                $newValues = array_filter(array_map('trim', explode(',', $valuesString)));
                
                // Get existing values
                $existingValues = $attribute->values()->pluck('value', 'id')->toArray();
                
                // Track which existing IDs we want to keep
                $valuesToKeep = [];
                
                // Update existing values or create new ones
                foreach ($newValues as $index => $newValue) {
                    $found = false;
                    
                    // Try to match with existing value
                    foreach ($existingValues as $id => $existingValue) {
                        if ($existingValue === $newValue && !in_array($id, $valuesToKeep)) {
                            $valuesToKeep[] = $id;
                            $found = true;
                            break;
                        }
                    }
                    
                    // If not found in existing values, create new
                    if (!$found) {
                        $created = $attribute->values()->create(['value' => $newValue]);
                        $valuesToKeep[] = $created->id;
                    }
                }
                
                // Delete only the values that are no longer needed
                $attribute->values()
                    ->whereNotIn('id', $valuesToKeep)
                    ->delete();
                    
            } else {
                // If no values provided, delete all
                $attribute->values()->delete();
            }
        } else {
            // switched to 'text' — remove any existing values
            $attribute->values()->delete();
        }

        return redirect()->route('attributes.index')->with('message', 'Attribute updated successfully!');
    }

    // Delete attribute (delete values and reindex)
    public function destroy(Attribute $attribute)
    {
        DB::transaction(function () use ($attribute) {
            // remove values to avoid orphans
            $attribute->values()->delete();
            $attribute->delete();

            // reindex to remove gaps and keep sort_order compact
            $this->reindexSortOrder();
        });

        return redirect()->route('attributes.index')->with('message', 'Attribute deleted successfully!');
    }

    /**
     * Move an attribute up (swap with previous by sort_order)
     * Note: route should POST to this method.
     */
    public function moveUp($id)
    {
        DB::transaction(function () use ($id) {
            $attr = Attribute::where('id', $id)->lockForUpdate()->firstOrFail();

            $prev = Attribute::where('sort_order', '<', $attr->sort_order)
                ->orderBy('sort_order', 'desc')
                ->lockForUpdate()
                ->first();

            if ($prev) {
                $tmp = $attr->sort_order;
                $attr->sort_order = $prev->sort_order;
                $prev->sort_order = $tmp;
                $attr->save();
                $prev->save();
            }
        });

        return back()->with('message', 'Attribute moved up.');
    }

    /**
     * Move an attribute down (swap with next by sort_order)
     * Note: route should POST to this method.
     */
    public function moveDown($id)
    {
        DB::transaction(function () use ($id) {
            $attr = Attribute::where('id', $id)->lockForUpdate()->firstOrFail();

            $next = Attribute::where('sort_order', '>', $attr->sort_order)
                ->orderBy('sort_order', 'asc')
                ->lockForUpdate()
                ->first();

            if ($next) {
                $tmp = $attr->sort_order;
                $attr->sort_order = $next->sort_order;
                $next->sort_order = $tmp;
                $attr->save();
                $next->save();
            }
        });

        return back()->with('message', 'Attribute moved down.');
    }

    /**
     * Reorder via AJAX (accepts ordered array of ids)
     * Body: { ids: [3,5,2,1] }
     */
    public function reorder(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Attribute::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    // helper to reindex orders sequentially
    private function reindexSortOrder()
    {
        $attrs = Attribute::orderBy('sort_order')->get();
        foreach ($attrs as $i => $a) {
            $a->sort_order = $i + 1;
            $a->save();
        }
    }
}
