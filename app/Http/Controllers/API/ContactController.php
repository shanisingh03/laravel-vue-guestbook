<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contact;
use Auth;

class ContactController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return Contact::paginate(4);
	}

	public function findUser()
	{
		if ($search = \Request::get('q')) {
			$contacts = Contact::where(function ($q) use ($search) {
				$q->where('name', 'LIKE', "%$search%")->orWhere('mobile', 'LIKE', "%$search%");
			})->paginate(4);

			return $contacts;
		}
		return Contact::paginate(4);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		// Validate Contact Data
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
			'name' => 'required|string|max:191',
			'mobile' => 'required|numeric|unique:contacts,mobile,' . $request['user_id'] . ',user_id',
			'email' => 'required|string|email|max:191'
		]);

		// Create Contact And Return Response
		return Contact::create([
			'user_id' => $request['user_id'],
			'name' => $request['name'],
			'email' => $request['email'],
			'mobile' => $request['mobile'],
			'is_fav' => $request['is_fav'],
			'comment' => $request['comment']
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		// Find Or Fail
		$contact = Contact::findOrFail($id);

		// Validate Contact Data
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
			'name' => 'required|string|max:191',
			'mobile' => 'required|numeric|unique:contacts,mobile,' . $request['user_id'] . ',user_id',
			'email' => 'required|string|email|max:191'
		]);

		$contact->update($request->all());

		return ['message' => 'Contact Updated Successfully.'];
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//Find Or Fail
		$contact = Contact::FindOrFail($id);

		// Delete Contact
		$contact->delete();

		// Return Response
		return ['message' => 'Contact Deleted Successfully.'];
	}
}
