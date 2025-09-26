<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\CustomerThankYouMail;
use App\Mail\AdminNewCustomerNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customer::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $request['phone'],
            // 'city' => $request['city'],
            // 'address' => $request['address'],
            // 'zip_code' => $request['zip_code'],
            // 'date_of_birth' => $request['date_of_birth'],
            'password' => Hash::make($validated['password']),
        ]);

            // Send thank you email to customer
            Mail::to($customer->email)->send(new CustomerThankYouMail($customer));

            // Send notification to admin
            $adminEmail = config('mail.admin_email'); // Make sure to add this to your .env
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminNewCustomerNotification($customer));
            }


        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'customer' => $customer,
        ]);
    }
    catch (\Exception $e) {
        logger()->error('Registration error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
    }

    public function login(Request $request)
    {
        try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'customer' => $customer,
        ]);
        }
        catch (\Exception $e) {
            logger()->error('Registration error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function logout(Request $request)
    {   
        try{
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
        }
         catch (\Exception $e) {
            logger()->error('Registration error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function user(Request $request)
    {   
        try{
        return response()->json($request->user());
        }
        catch (\Exception $e) {
            logger()->error('Registration error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function checkuser(Request $request)
    {   
        try{
        $customer = Customer::where('email', $request->email)->first();

        if ($customer) {
                return response()->json(['success' => "User found"], 200);
        } else {
                 return response()->json(['error' => "New user"], 500);
        }

        }
        catch (\Exception $e) {
            logger()->error('Registration error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }


     public function updateuser(Request $request)
    {
            try {
                $validated = $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                ]);

                    // Find by email
                    $customer = Customer::where('email', $request['email'])->first();

                    // Or find by user_id
                    // $customer = Customer::where('user_id', $validated['user_id'])->first();

                    if ($customer) {
                    $customer->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $request['phone'],
                    'city' => $request['city'],
                    'address' => $request['address'],
                    'address_2' => $request['address_2'],
                    'zip_code' => $request['zip_code'],
                    'state' => $request['state'],
                    'date_of_birth' => $request['date_of_birth'],
                    ]);
                    }

                

                return response()->json([
                    
                    'customer' => $customer,
                ]);
            }
            catch (\Exception $e) {
                logger()->error('Registration error:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
    }

             public function passwordchange(Request $request)
            {
                    try {
                            
                                $validated = $request->validate([
                                'password' => 'required|string|min:8',
                                'password_confirmation' => 'required|string|same:password',
                                ]);

                            $customer = Customer::where('email', $request['email'])->first();


                            if ($customer) {
                            $customer->update([
                                'password' => bcrypt($validated['password_confirmation']),
                            ]);
                            }

                        

                       return response()->json(['success' => "Password Change"], 200);

                    }
                    catch (\Exception $e) {
                        logger()->error('Registration error:', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return response()->json(['error' => $e->getMessage()], 500);
                    }
            }
}