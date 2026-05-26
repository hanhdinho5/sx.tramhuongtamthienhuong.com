<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        return view('admin.pages.settings.index');
    }

    public function appSetting(Request $request)
    {
        try {
            $dataFields = [
                "og_title", "og_des", "og_url", "og_site", "brand_name", "home_name",
                "qna_email", "browser_title", "meta_tag", "meta_keyword", "domain_url",
                "email", "phone", "address", "address_detail", "zip",
                "owner_name", "owner_phone", "owner_email",
                "owner_social_01", "owner_social_02", "owner_social_03",
                "company_name", "fax", "business_number",
                "author_name", "author_social", "copyright",
                "google_site_verification",
                "bank_name", "bank_number", "bank_holder",
                "bank_name_02", "bank_number_02", "bank_holder_02",
            ];

            $data = array_combine($dataFields, array_map(fn($field) => $request->input($field), $dataFields));

            if ($request->hasFile('logo')) {
                $item = $request->file('logo');
                $itemPath = $item->store('setting', 'public');
                $logo = asset('storage/' . $itemPath);

                $data['logo'] = $logo;
            }

            if ($request->hasFile('og_img')) {
                $item = $request->file('og_img');
                $itemPath = $item->store('setting', 'public');
                $og_img = asset('storage/' . $itemPath);

                $data['og_img'] = $og_img;
            }

            if ($request->hasFile('favicon')) {
                $item = $request->file('favicon');
                $itemPath = $item->store('setting', 'public');
                $favicon = asset('storage/' . $itemPath);

                $data['favicon'] = $favicon;
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailPaths = array_map(function ($image) {
                    $itemPath = $image->store('setting', 'public');
                    return asset('storage/' . $itemPath);
                }, $request->file('thumbnail'));
                $thumbnail = implode(',', $thumbnailPaths);

                $data['thumbnail'] = $thumbnail;
            }

            if ($request->hasFile('qr_code')) {
                $item = $request->file('qr_code');
                $itemPath = $item->store('setting', 'public');
                $favicon = asset('storage/' . $itemPath);

                $data['qr_code'] = $favicon;
            }

            if ($request->hasFile('qr_code_02')) {
                $item = $request->file('qr_code_02');
                $itemPath = $item->store('setting', 'public');
                $favicon = asset('storage/' . $itemPath);

                $data['qr_code_02'] = $favicon;
            }

            $setting = Setting::firstOrNew([]);
            $setting->fill($data);
            $setting->save();

            toast('Save successfully!', 'success', 'top-left');
            return redirect(route('admin.app.setting.index'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            toast('Error, Please try again!', 'error', 'top-left');
            return back();
        }
    }
}
