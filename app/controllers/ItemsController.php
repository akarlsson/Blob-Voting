<?php

class ItemsController extends BaseController {

    /**
     * Item Repository
     *
     * @var Item
     */
    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->item->all();
		
		if (Session::get('isadmin')=='true') {
        	return View::make('items.index', compact('items'));
		} else {
			return "Not allowed.";
			}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {	
		$facebook = new Facebook(array(
		  'appId'  => '458082764272869',
		  'secret' => 'af0ee91b1ffb7a4c8f6c3b14758eb095',
		));
        return View::make('items.create', compact('facebook'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $inputcheck = Input::all();
		$input = Input::only('name', 'body', 'image', 'author');
		
		$facebook = new Facebook(array(
		  'appId'  => '458082764272869',
		  'secret' => 'af0ee91b1ffb7a4c8f6c3b14758eb095',
		));
		$user = $facebook->getUser();
        
		$checkuser = Item::where('author','=',$user)->count();
		
		if ($checkuser==0||Session::get('isadmin')=='true') {
		
			$validation = Validator::make($inputcheck, Item::$rules);
			
			if (Input::hasFile('image'))
			{	//return Input::file('image')->getClientOriginalExtension();
				$filename = Str::random(47) .'.'. Input::file('image')->getClientOriginalExtension();
				$image = Input::file('image');
				$folder = public_path().'/photos/';
				Input::file('image')->move($folder,$filename);
				//$path = Input::file('image')->getRealPath();
				$input["image"] = $filename;
				
				$fullfilepathandname = $folder.$filename;			
			
				$image = ImgW::initFromPath($fullfilepathandname);
				
				// Resize to 600 wide maintain ratio
				$image->resizeInPixel(800, null, true);
				 
				$watermark = ImgW::initFromPath( public_path().'/img/logo.png');
				 
				$image->addLayerOnTop($watermark, 20, 10, 'RB');
				 
				// Save over original
				$image->save($folder, $filename);			
				
			}
			
			
			if ($validation->passes())
			{
				$this->item->create($input);
	
				return Redirect::to('/hello')->with('flash_message', '<p class="message">Vielen Dank für deine Bewerbung. Dein Eintrag wird von der Redaktion geprüft und freigeschalten. <strong>Die Votingphase startet am ' . date("d.m.Y", strtotime(Config::get("app.votingstart"))) . '.</strong></p>');;
			} else {
				return Redirect::route('items.create')
				->withInput()
				->withErrors($validation)
				->with('flash_message', '<p class="error">Bei der Verarbeitung des Formulars sind Fehler aufgetreten.</p>');
				}
			
		} else {
			
			return Redirect::route('items.create')
				->withInput()
				->with('flash_message', '<p class="error">Du hast dich bereits beworben.</p>');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $item = $this->item->findOrFail($id);
		
		if (Holmes::is_mobile()==false) {
			return Redirect :: to (Config::get('app.fbtablink').'&'.rand(0,3000000).'&app_data=detail'.$item->id);
			//return Redirect :: to (Request::root().'/?app_data=detail'.$item->id);
			//return View::make('items.show', compact('item'));
		} else {
		   //return View::make('items.show', compact('item'));
		   return Redirect :: to (Request::root().'/?app_data=detail'.$item->id);
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
        $item = $this->item->find($id);

        if (is_null($item))
        {
            return Redirect::route('items.index');
        }

        return View::make('items.edit', compact('item'));
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
        //$validation = Validator::make($input, Item::$rules);
		
		if (Input::hasFile('image'))
		{	//return Input::file('image')->getClientOriginalExtension();
		    $filename = Str::random(47) .'.'. Input::file('image')->getClientOriginalExtension();
			$image = Input::file('image');
			$folder = public_path().'/photos/';
			Input::file('image')->move($folder,$filename);
			//$path = Input::file('image')->getRealPath();
			$input["image"] = $filename;
			
			$fullfilepathandname = $folder.$filename;			
			
			$image = ImgW::initFromPath($fullfilepathandname);
			
			// Resize to 600 wide maintain ratio
			$image->resizeInPixel(800, null, true);
			 
			$watermark = ImgW::initFromPath( public_path().'/img/bloblogo.png');
			 
			$image->addLayerOnTop($watermark, 20, 10, 'RB');
			 
			// Save over original
			$image->save($folder, $filename);			
			
		}
		
        //if ($validation->passes())
       // {
            $item = $this->item->find($id);
			
			if (!Input::hasFile('image')){
				$input["image"] = $item->image;
			}
			
            $item->update($input);
			//echo $item->image;
           return Redirect::route('items.index');
       // }

       /* return Redirect::route('items.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('flash', 'There were validation errors.');*/
		
	} else {
		return "Not Allowed.";
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
        $this->item->find($id)->delete();

        return Redirect::route('items.index');
		} else {
			return "Not Allowed";
			}
    }
	

}