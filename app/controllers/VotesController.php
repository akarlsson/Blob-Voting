<?php

class VotesController extends BaseController {

    /**
     * Vote Repository
     *
     * @var Vote
     */
    protected $vote;

    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $votes = $this->vote->all();

        return View::make('votes.index', compact('votes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('votes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
		
		$vote = Vote::new();
		
		$facebook = new Facebook(array(
		  'appId'  => '458082764272869',
		  'secret' => 'af0ee91b1ffb7a4c8f6c3b14758eb095',
		));
		
		// Get User ID
		$user = $facebook->getUser();
		
		$input->fbid = $user;
        $validation = Validator::make($input, Vote::$rules);

        if ($validation->passes())
        {
            $this->vote->create($input);

            return Redirect::route('votes.index');
        }

        return Redirect::route('votes.create')
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
        $vote = $this->vote->findOrFail($id);

        return View::make('votes.show', compact('vote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $vote = $this->vote->find($id);

        if (is_null($vote))
        {
            return Redirect::route('votes.index');
        }

        return View::make('votes.edit', compact('vote'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = array_except(Input::all(), '_method');
        $validation = Validator::make($input, Vote::$rules);

        if ($validation->passes())
        {
            $vote = $this->vote->find($id);
            $vote->update($input);

            return Redirect::route('votes.show', $id);
        }

        return Redirect::route('votes.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('flash', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->vote->find($id)->delete();

        return Redirect::route('votes.index');
    }

}