<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\AttributeRequest;
use App\Models\Attribute;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected $service;

    public function __construct(AttributeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $attributes = $this->service->all();
        return view('dashboard.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('dashboard.attributes.create');
    }

    public function store(AttributeRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully!');
    }

    public function edit(Attribute $attribute)
    {
        $attribute->load('options');
        return view('dashboard.attributes.edit', compact('attribute'));
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $this->service->update($attribute, $request->validated());

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully!');
    }

    public function destroy(Attribute $attribute)
    {
        if (request()->ajax()) {
            $this->service->delete($attribute);
            return response()->json(['success' => true, 'message' => 'Attribute deleted successfully!']);
        }

        $this->service->delete($attribute);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully!');
    }
    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,activate,deactivate,required,not_required,filterable,not_filterable',
        ]);

        $this->service->bulkAction($request->ids, $request->action);

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }
}
