<?php

namespace App\Http\Controllers\Admin\General;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public $route = 'statistics';
    public $view = 'admin-panel.general.statistics';

    public function index()
    {
        try {
            $statistics = Statistic::orderBy('order')->get();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('statistics'));
    }

    public function create()
    {
        return view("$this->view.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'unit_ar' => 'required',
            'unit_en' => 'required',
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'order' => 'integer',
        ], [
            'number.required' => __('adminlte::adminlte.number_required'),
            'unit_ar.required' => __('adminlte::adminlte.unit_required'),
            'unit_en.required' => __('adminlte::adminlte.unit_en_required'),
        ]);

        try {
            $data = $request->all();
            $data['active'] = $request->has('active') ? $request->active : true;
            Statistic::create($data);
            return redirect()->route("$this->route.index", ['locale' => app()->getLocale()])->with('success', __('adminlte::adminlte.save_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($locale, $id)
    {
        try {
            $statistic = Statistic::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact('statistic'));
    }

    public function update(Request $request, $locale, $id)
    {
        $request->validate([
            'number' => 'required|numeric',
            'unit_ar' => 'required',
            'unit_en' => 'required',
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'order' => 'integer',
        ], [
            'number.required' => __('adminlte::adminlte.number_required'),
            'unit_ar.required' => __('adminlte::adminlte.unit_required'),
            'unit_en.required' => __('adminlte::adminlte.unit_en_required'),
        ]);

        try {
            $statistic = Statistic::findOrFail($id);
            $data = $request->all();
            $data['active'] = $request->has('active') ? $request->active : $statistic->active;
            $statistic->update($data);
            return redirect()->route("$this->route.index", ['locale' => app()->getLocale()])->with('success', __('adminlte::adminlte.update_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function toggleActive($locale, $id)
    {
        try {
            $statistic = Statistic::findOrFail($id);
            $statistic->active = !$statistic->active;
            $statistic->save();

            $message = $statistic->active ? __('adminlte::adminlte.succActivate') : __('adminlte::adminlte.succDeactivate');
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($locale, $id)
    {
        try {
            $statistic = Statistic::findOrFail($id);
            $statistic->delete();
            return redirect()->route("$this->route.index", ['locale' => app()->getLocale()])->with('success', __('adminlte::adminlte.delete_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
