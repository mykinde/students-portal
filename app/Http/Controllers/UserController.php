<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate();

       /*  return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage()); */

            if(Auth::user()->admin < 1){
                $users = \App\Models\User::where('id',   Auth::user()->id)->get();
            }else{
                $users = \App\Models\User::latest()->paginate(2);
                //$users = $this->userRepository->paginate(5);
                } 
                return view('users.index')
                ->with('users', $users);
    }



         

    /**
    * @return \Illuminate\Support\Collection
    */

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');

    }

       

    /**
    * @return \Illuminate\Support\Collection
    */

    public function import() 
    {
          Excel::import(new UsersImport,request()->file('file'));
             return back();

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        return view('users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
       // User::find($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
