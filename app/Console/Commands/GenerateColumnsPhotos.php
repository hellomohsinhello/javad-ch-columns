<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Weidner\Goutte\GoutteFacade;

class GenerateColumnsPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-columns-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

//        connection timeout off

        $page = 995;
        while ($page--) {
            $crawler = GoutteFacade::request('GET', 'https://www.dailyurdunews.com/javedchaudhry/category/zero-point-columns/page/'.$page.'/');

//    dd($crawler->find('div[class="post-content"]'));

//    find a image tag and extract the src attribute.
            $crawler->filter('img')->each(function ($node) use ($page) {

                try {
                    $image = file_get_contents($node->attr('src'));

                    $imageName = substr($node->attr('src'), strrpos($node->attr('src'), '/') + 1);



                    if ($imageName == 'javed-chaudhry.jpg') {
                        return;
                    }

                    if (Storage::disk('public')->exists($imageName)) {

                        $this->info($page.' Image already exists: ' . $imageName);

                    }else{
                        Storage::disk('public')->put($imageName, $image);

                        $this->info($page.' Image saved: ' . $imageName);
                    }
                }catch (\Exception $exception){
                    $this->info($page.' Image not found: ' . $node->attr('src'));
                }

            });
        }
    }
}
