<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Form;
use App\Models\FormEntries;
use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Frontend\FrontendController;
use Mail;
use App\Mail\SendMail;
use App\Http\Controllers\ConstantsController;

class FormController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the landing page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $slug )
    {
        $this->append_breadcrumbs( ucfirst( $slug ), route( 'form.show', [$slug] ) );

        return view( 'frontend.'.$this->active_theme->theme_abrv.'.'.( $slug ) );
    }

    public function store( Request $request, $slug )
    {
        $dataArray = $request->all();
        //prr($dataArray);
        $dataToSave = [
            'name'    => $dataArray['fullname'],
            'email'   => $dataArray['email'],
            'company' => $dataArray['company'],
            'phone'   => $dataArray['phone'],
            'details' => $dataArray['Inquiry']
        ];

        if ( Auth::user() )
        {
            $dataToSave['customer_id'] = Auth::user()->customer_id;
        }

        $response = ContactUs::insert( $dataToSave );

        if ( $response == 1 )
        {
            return back()->with( 'success', 'We have received your Inquiry request, We will contact you soon' );
        }
        else
        {
            return back()->with( 'error', 'Something is going wrong while processing you request' );
        }

    }

    public function submission_request( Request $request, $slug )
    {

        if ( $request->all() )
        {

            $data = $request->all();

            if ( $request->hasFile( 'attachment' ) && $request->file( 'attachment' )->isValid() )
            {
                $data['attachment'] = CommonController::upload_file_ftp( $request->attachment ); //  $request->attachment->store( 'storage' )
            }

            $form = Form::where( 'slug', $slug )->firstOrFail();

            $form_entry          = new FormEntries();
            $form_entry->form_id = $form->id;
            $form_entry->data    = json_encode( $data );
            $form_entry->save();
            
            if ( isset($this->active_theme_json->general->allow_emails) && $this->active_theme_json->general->allow_emails )
            {
                try {
                    Mail::to(ConstantsController::ADMIN_EMAIL)->bcc('testing.demo.as@gmail.com') ->send(new SendMail( $data, ucwords( str_replace('_',' ',$slug) ) ));
                }
                catch(\Exception $e){
                    prr("Mail Exception: " . $e->getMessage());
                }
            }

            return redirect()->back()->with( 'message', ['type' => 'success', 'referrer' => $slug, 'body' => $slug == 'newsletter' ? 'Thanks for subscribing!' : 'Thanks for filling out the form. Our team will be in touch with you soon.'] );
        }

        return redirect()->back()->with( 'message', ['type' => 'success', 'referrer' => $slug, 'body' => 'Thanks for filling out the form. Our team will be in touch with you soon.'] );
    }

}
