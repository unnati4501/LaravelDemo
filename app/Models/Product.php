<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'price', 'quantity'];

    public static function getProductDataTable($request)
    {
        $query = self::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                if ($request->category) {
                    $query->where('category', $request->category);
                }

                if ($request->search && $request->search['value'] != '') {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('category', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('products.edit', $row->id);
                return '
                    <a href="'.$editUrl.'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}