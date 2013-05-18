<?php

class UsersController extends BaseController {

    /**
     * User Repository
     *
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		if (Session::get('isadmin')=='true') {
        $users = $this->user->all();

        return View::make('users.index', compact('users'));
		} else {
			return "Not Allowed";
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $validation = Validator::make($input, User::$rules);

        if ($validation->passes())
        {
			return $input;
            //$this->user->create($input);

            //return Redirect::route('users.index');
        }

        return Redirect::route('users.create')
            ->withInput()
            ->withErrors($validation)
            ->with('flash', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
		if (Session::get('isadmin')=='true') {
        $user = $this->user->findOrFail($id);

        return View::make('users.show', compact('user'));
		} else {
			return "Not Allowed";
			}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
		if (Session::get('isadmin')=='true') {
        $user = $this->user->find($id);

        if (is_null($user))
        {
            return Redirect::route('users.index');
        }

        return View::make('users.edit', compact('user'));
		} else {
			return "Not Allowed.";
			}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
		if (Session::get('isadmin')=='true') {
        $input = array_except(Input::all(), '_method');
        $validation = Validator::make($input, User::$rules);

        if ($validation->passes())
        {
            $user = $this->user->find($id);
            $user->update($input);

            return Redirect::route('users.show', $id);
        }

        return Redirect::route('users.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('flash', 'There were validation errors.');
		} else {
			return "Not Allowed";
			}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
	
    {
    if (Session::get('isadmin')=='true') {
	    $this->user->find($id)->delete();

        return Redirect::route('users.index');
	} else {
		return "Not Allowed.";
		}
    }

}