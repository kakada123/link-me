<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkDetail;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        $linkDetail = LinkDetail::findOrFail($id);
        $linkDetail->update($request->all());

        return redirect()->back()
            ->with('success', 'Link updated successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'link_id' => 'required|exists:links,id'
        ]);

        LinkDetail::create([
            'link_id' => $request->get('link_id'),
        ]);

        return redirect()->back()
            ->with('success', 'Link detail created successfully');
    }
}
