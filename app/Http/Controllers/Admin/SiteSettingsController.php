<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    /**
     * Hiển thị form cập nhật thông tin site.
     */
    public function edit(): View
    {
        $site = SiteSetting::get();
        return view('admin.site.edit', compact('site'));
    }

    /**
     * Cập nhật thông tin site.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'footer_description' => ['nullable', 'string', 'max:500'],
            'address' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'hotline' => ['nullable', 'string', 'max:50'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
        ], [
            'site_name.required' => 'Tên site không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'facebook_url.url' => 'Đường dẫn Facebook phải là URL hợp lệ.',
        ]);

        $site = SiteSetting::get();
        $site->update($validated);

        return redirect()->route('admin.site.edit')->with('success', 'Đã cập nhật thông tin site.');
    }
}
