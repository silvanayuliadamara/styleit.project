<?php

namespace App\Http\Controllers;

use App\Support\PreviewData;
use Illuminate\Http\Response;

class PublicPageController extends Controller
{
    public function home()
    {
        $categories = PreviewData::categories();
        $portfolioItems = PreviewData::portfolio()->take(4);

        return view('home', compact('categories', 'portfolioItems'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function layanan()
    {
        $categories = PreviewData::categories();
        return view('layanan.index', compact('categories'));
    }

    public function kategori(string $slug)
    {
        $category = PreviewData::categoryBySlug($slug);
        abort_if(! $category, Response::HTTP_NOT_FOUND);

        return view('layanan.kategori', compact('category'));
    }

    public function paket(string $code)
    {
        $package = PreviewData::packageByCode($code);
        abort_if(! $package, Response::HTTP_NOT_FOUND);

        $addons = PreviewData::addons();
        $calendar = PreviewData::calendarFor($package);

        return view('paket.show', compact('package', 'addons', 'calendar'));
    }

    public function portofolio()
    {
        $items = PreviewData::portfolio();
        return view('portofolio', compact('items'));
    }

   public function pricelist()
{
    $categories = PreviewData::categories();
    $packages = PreviewData::packages();

    return view('pricelist', compact('categories', 'packages'));
}
}
