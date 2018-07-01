<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 收货列表
     */
    public function index(Request $request)
    {
        return view('user_addresses.index',[
            'addresses'=> $request->user()->addresses,
        ]);
    }

    // 新增收货列表
    public function create()
    {
        return view('user_addresses.create', ['address'=>new UserAddress()]);
    }

    // 新增收货地址
    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_address_index');
    }

    public function edit(UserAddress $userAddress)
    {
        return view('user_addresses.create', ['address'=>$userAddress]);
    }

    public function update(UserAddress $userAddress, UserAddressRequest $request)
    {
        $this->authorize('own', $userAddress);
        $userAddress->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_address_index');
    }

    public function destroy(UserAddress $userAddress)
    {
        $this->authorize('own', $userAddress);
        $userAddress->delete();
//        return [];
        return redirect()->route('user_address_index');
    }
}
