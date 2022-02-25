<?php

namespace App\Http\Controllers;

use App\Models\PecsfCharity;
use App\Models\PecsfRegion;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class PecsfController extends Controller
{

    /**
     * Return list of PECSF charities
     *
     * @param Integer $milestone
     * @return \Illuminate\Http\Response
     */

    public function getCharities()
    {
        return PecsfCharity::all();
    }

    /**
     * Return list of PECSF regions
     *
     * @param Integer $milestone
     * @return \Illuminate\Http\Response
     */

    public function getRegions()
    {
        return PecsfRegion::all();
    }


}
