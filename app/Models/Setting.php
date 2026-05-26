<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
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
        "logo", "og_img", "favicon", "thumbnail", "qr_code", "qr_code_02",
    ];
}
