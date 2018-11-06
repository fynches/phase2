<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Laravel\Socialite\Contracts\Provider;
use Auth;
use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\LinkedInProvider;
use League\Flysystem\Exception;
use Session;

class SocialAccountService
{

    public function createOrGetUser(Provider $provider ,$providersName)
    {
        $providerUser = $provider->user() ;
        $providerName = class_basename($provider);
        $accountB = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();
        Session::set('account', $providerUser);
        if ($providersName == 'facebook') {
            $fb = new \Facebook\Facebook([
                'app_id' => env('FACEBOOK_CLIENT_ID'),
                'app_secret' =>  env('FACEBOOK_CLIENT_SECRET'),
                'default_graph_version' => 'v2.9',
                'cookie' => true,
                //'default_access_token' => '{access-token}', // optional
            ]);
            $oAuth2Client = $fb->getOAuth2Client();
            $longToken = $oAuth2Client->getLongLivedAccessToken($providerUser->token);
        }
        if($accountB)
        {
            // facebook
            if ($providersName == 'facebook')
            {
                
              /*  Friends::where('friends1', $providerUser->id)->orwhere('friends2', $providerUser->id)->delete();
                if (isset($providerUser['context']['mutual_friends'])) {
                    $friends = $providerUser['context']['mutual_friends']['data'];
                    $i = 0;
                    foreach ($friends as $friend){
                        $mutualFrId[$i]['friends1'] =  $providerUser->id;
                        $mutualFrId[$i]['friends2'] =  $friend['id'];
                        $i++;
                    }
                    Friends::insert($mutualFrId);
                }*/
                SocialAccount::whereUserId($accountB->user->id)->update(['longToken' => $longToken]);

            }
            //dd($accountUserI);
            Auth::login($accountB->user);
            $accountB->user->update(['updated_at' => new Carbon]);
            return true;
        }


        $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

        $user = User::whereEmail($providerUser->getEmail())
            ->first();

        if ($user)
        {
            if(!$accountB){
                 SocialAccount::create([
                    'user_id'=>$user->id,
                    'provider_user_id' => $providerUser->getId(),
                    'provider' => $providerName,
                    'longToken' => $longToken ?? ""
                ]);
            }
            //save users friends
         /*   if ($providersName == 'facebook') {
                Friends::where('friends1', $providerUser->id)->orwhere('friends2', $providerUser->id)->delete();
                if (isset($providerUser['context']['mutual_friends'])) {
                    $friends = $providerUser['context']['mutual_friends']['data'];
                    $i = 0;
                    foreach ($friends as $friend){
                        $mutualFrId[$i]['friends1'] =  $providerUser->id;
                        $mutualFrId[$i]['friends2'] =  $friend['id'];
                        $i++;
                    }
                    Friends::insert($mutualFrId);
                }
            }*/

            Auth::login($user);
            return true;
        }else{
            $account_details = [
                'email'      => $providerUser->getEmail(),
                'name'       => $providerUser->getName(),
                'first_name' => '',
                'last_name'  => '',
            ];
            // linkedin
            if ($providersName == 'linkedin')
            {

                 //account
                $account_details['first_name'] = $providerUser->user['firstName'] ?? null;
                $account_details['last_name'] = $providerUser->user['lastName'] ?? null;
                $account_details['name'] = $providerUser->user['formattedName'] ?? null;
                $account_details['email'] = $providerUser->user['emailAddress'] ?? null;
                   ///avatar
                    if (isset($providerUser->user['pictureUrls']['values'][0])){
                       try{
                           $avatarsName = $account_details['first_name']. $account_details['last_name'].time().'.jpg';
                           copy($providerUser->user['pictureUrls']['values'][0], storage_path('app/' . $avatarsName));
                           File::copy(storage_path('app/' . $avatarsName), public_path(User::PHOTO_PATH . $avatarsName));
                           $account_details['avatar'] = $avatarsName;
                           $account_details['avatar_type'] = User::AVATAR_TYPE__IMAGE;
                       }catch(Exception $e){

                        }
                    }
                   ///user location
               // dd($providerUser->user);
                if(isset($providerUser->user['location']['name'])){
                    $location = explode(',',$providerUser->user['location']['name']);
                    $postalCode = $providerUser->user['location']['country']['code'];
                    if(count($location) == 1){
                        $userLocation = Location::where('country','%LIKE%',$location[0])->first();
                    }else{
                        $userLocation = Location::where('iso_code',$postalCode)->where('city', $location[0])->first();
                    }
                }
                //industry
                if(isset($providerUser->user['industry']) && $industry = Industryexperience::whereName($providerUser->user['industry'])->first()){
                    $user_details['industry'] = $industry->id;
                }elseif (isset($providerUser->user['industry']) && $industry = Industryexperience::create(['name' => $providerUser->user['industry']])){
                }

                if (isset($providerUser->user['positions']['values'][0]['startDate']['year']) && !isset($providerUser->user['positions']['values'][0]['startDate']['month'])){
                    $user_details['industryexperiences'] = Carbon::createFromDate($providerUser->user['positions']['values'][0]['startDate']['year'])->diffInYears(new Carbon);
                }elseif(isset($providerUser->user['positions']['values'][0]['startDate']['year']) && isset($user['positions']['values'][0]['startDate']['month'])){
                    $user_details['industryexperiences'] = Carbon::createFromDate($providerUser->user['positions']['values'][0]['startDate']['year'], $providerUser->user['positions']['values'][0]['startDate']['month'])->diffInYears(new Carbon);
                }

                //positions
                if(isset($providerUser->user['positions']['values'][0]['location']['country']['name']) && isset($providerUser->user['positions']['values'][0]['location']['name']) ){
                    $companylocation =  Location::whereCountry($providerUser->user['positions']['values'][0]['location']['country']['name'])->whereCity(explode(',',$providerUser->user['positions']['values'][0]['location']['name'])[0])->first();
                    $companylocationId = $companylocation->id;
                }

                if(isset($providerUser->user['positions']['values'][0]['company']['name']) && $company = Company::whereName($providerUser->user['positions']['values'][0]['company']['name'])->first()){
                    $position_details['company'] = $company->id;
                }elseif(isset($providerUser->user['positions']['values'][0]['company']['name'])){
                    $company = Company::create(['name' =>$providerUser->user['positions']['values'][0]['company']['name'],'location' => $companylocationId ?? ""]);
                }

                if(isset($providerUser->user['positions']['values'][0]['title']) && $bestD = Bestdescribed::whereName($providerUser->user['positions']['values'][0]['title'])->first()){
                    $position = Position::create(['name' => $providerUser->user['positions']['values'][0]['title'], 'summary' => $providerUser->user['positions']['values'][0]['title'] ?? "", 'is-current' => 1]);
                    if(isset($company) && is_object($company) && is_object($position)){
                        $company->positions()->save($position,['position_id' => $position->id]);
                    }
                    $position_details['position'] = $position->id;
                }elseif ( isset($providerUser->user['positions']['values'][0]['title']) && $bestD = Bestdescribed::create(['name' => $providerUser->user['positions']['values'][0]['title']])){
                    $position = Position::create(['name' => $providerUser->user['positions']['values'][0]['title'], 'summary' => $providerUser->user['positions']['values'][0]['title'] ?? "", 'is-current' => 1]);
                    if(isset($company) && is_object($company) && is_object($position)){
                        $company->positions()->save($position,['position_id' => $position->id]);
                    }
                }



                //$position_details['industryexperiences'] = Carbon::createFromDate($user['positions']['values'][0]['location'], $month);

               // = $user['location']['country']['name'] ?? "" . $user['location']['name'] ?? "" || "";

                if($account_details['email'] == null){
                    $account_details['email'] =  $account_details['first_name'].$account_details['last_name'].$providerUser->user['id']."@linkedin.com";
                }

                $user = User::create($account_details);

                 if(isset($userLocation)){
                     $user->locations()->save($userLocation,['locations_id' => $userLocation->id]);
                 }

                if(isset($industry) && is_object($industry)){
                    $user->industryexperiences()->save($industry,['industryexperience_id' => $industry->id]);
                }

                if(isset($position) && is_object($position)){
                    $user->positions()->save($position,['position_id' => $position->id, 'company_id' => 0]);
                }

                if(isset($bestD) && is_object($bestD)){
                    $user->bestdescribeds()->save($bestD,['bestdescribed_id' => $bestD->id]);
                }


            }

            // facebook
            if ($providersName == 'facebook')
            {

                $fullname = explode(' ', $providerUser->name);
                $account_details['first_name'] = isset($fullname[0]) ? $fullname[0] : '';
                $account_details['last_name'] = isset($fullname[1]) ? $fullname[1] : '';
                $account_details['name'] = $providerUser->name;


                if (isset($providerUser->avatar)){
                    try{
                        $avatarsName = $account_details['first_name']. $account_details['last_name'].time().'jpg';
                        copy($providerUser->avatar, storage_path('app/' . $avatarsName));
                        File::copy(storage_path('app/' . $avatarsName), public_path(User::PHOTO_PATH . $avatarsName));
                        $account_details['avatar'] = $avatarsName;
                        $account_details['avatar_type'] = User::AVATAR_TYPE__IMAGE;
                    }catch(Exception $e){

                    }
                }

               $user = User::create($account_details);


                ///user location
                if(isset($providerUser->user['location']['name'])){
                    $location = explode(',',$providerUser->user['location']['name']);
                    if(count($location) == 1){
                        $userLocation = Location::where('country','%LIKE%',trim($location[1]))->first();
                    }else{
                        $userLocation = Location::where('country',trim($location[1]))->where('city', trim($location[0]))->first();
                    }
                }


                if(isset($userLocation)){
                    $user->locations()->save($userLocation,['locations_id' => $userLocation->id]);
                }

                if(isset($providerUser['education'])) {
                    $educations = $providerUser['education'];
                    $educationArray = array();
                    $i = 0;
                    $educationUser = array();
                    foreach ($educations as $education) {
                        $educationArray[$i]['name'] = "";

                        if (isset($education['school'])) {
                            $educationArray[$i]['school']['name'] = $education['school']['name'];


                        }

                     /*   if (isset($education['type'])) {
                            $educationArray[$i]['school']['name'] .= $education['type'];
                        }*/

                        if (isset($education['concentration'])) {
                            foreach ($education['concentration'] as $concentration) {
                                $educationArray[$i]['name'] .= $concentration['name'] . ", ";
                            }
                        }elseif(isset($education['degree'])){
                            $educationArray[$i]['name'] .= $education['degree']['name'];
                        }

                        if (isset($education['year'])) {
                            $educationArray[$i]['end-date'] = $education['year']['name'] . "-0-0";
                            $educationArray[$i]['current'] = 1;
                        } else {
                            $educationArray[$i]['current'] = 0;
                        }
                       // dd($educationArray);

                        if (!$institute[$i] = Institute::whereName($educationArray[$i]['school']['name'])->first()) {
                            $institute[$i] = new Institute();
                            $institute[$i]->name = $educationArray[$i]['school']['name'];
                            $institute[$i]->save();
                            $institute[$i]->users()->save($user,['user_id' => $user->id]);
                        }


                        $educationUser[$i] = new Education($educationArray[$i]);
                        $educationUser[$i]->save();
                        $educationUser[$i]->institutes()->save($institute[$i], ['institute_id' => $institute[$i]->id]);
                        $educationUser[$i]->users()->save($user,['user_id' => $user->id]);

                        $i++;

                        if($i == 15){
                            break;
                        }
                    }
                }

                if (isset($providerUser['work'])) {
                    $workArray = array();
                    $works = $providerUser['work'];
                    $i = 0;
                    $company = array();
                    $position = array();
                    foreach ($works as $work) {

                        if (isset($work['position'])) {

                            if (isset($work['employer'])) {
                                $workArray[$i]['company']['name'] = $work['employer']['name'];
                                if (!$company[$i] = Company::whereName($workArray[$i]['company']['name'])->first()) {
                                    $company[$i] = new Company(['name' => $workArray[$i]['company']['name']]);
                                    $company[$i]->save();
                                }

                            }

                            $workArray[$i]['position']['name'] = $work['position']['name'];

                            if (isset($work['start_date'])) {
                                $workArray[$i]['position'][]['start-date'] = $work['start_date'];
                                if (!isset($work['end_date'])) {
                                    $workArray[$i]['position'][]['is-current'] = 1;
                                } else {
                                    $workArray[$i]['position'][]['end-date'] = $work['end_date'];
                                }
                            }

                            if (!$position[$i] = Position::whereName($workArray[$i]['position']['name'])->first()) {
                                $position[$i] = new Position($workArray[$i]['position']);
                                $position[$i]->save();
                            }
                            $position[$i]->companies()->save($company[$i],['company_id' => $company[$i] -> id]);
                            $company[$i]->users()->save($user,['user_id' => $user->id]);

                        }
                        $i++;
                        if($i == 15){
                            break;
                        }
                    }

                    if(isset(end($position)->name)) {
                        $lastPosition = end($position)->name;
                        if ($bestD = Bestdescribed::whereName($lastPosition)->first()) {
                        } elseif ($bestD = Bestdescribed::create(['name' => $lastPosition])) {
                            $user->bestdescribeds()->save($bestD, ['bestdescribed_id' => $bestD->id]);

                        }
                    }
                }



             /*   if (isset($educationUser) && count($educationUser) > 0) {
                    $i = 0;
                    foreach ($educationUser as $educationSave) {
                        $instituteId = $educationSave->instituteId;
                        unset($educationSave->instituteId);
                        $user->educations()->save($educationSave, ['institute_id' => $instituteId]);
                        $instituteUser = new Institute();
                        $instituteUser->users()->save($institute[$i],['user_id' => $user->id, 'institute_id' => $instituteId ]);
                        $i++;
                    }
                }*/

                if (isset($company) && count($company)>0) {
                    $i = 0;
                    foreach ($company as $companySave) {
                        $companySave->users()->save($companySave, ['user_id' => $user->id]);
                        if (isset($position[$i])) {
                            $position[$i]->users()->save($position[$i],['user_id' => $user->id, 'company_id' => $companySave->id]);
                        }
                        $i++;
                    }
                }


            }

            // twitter
            if ($providersName == 'twitter')
            {
                $fullname = explode(' ', $providerUser->name);
                $account_details['first_name'] = isset($fullname[0]) ? $fullname[0] : '';
                $account_details['last_name'] = isset($fullname[1]) ? $fullname[1] : '';
                $account_details['name'] = $providerUser->name;
                if (isset($providerUser->avatar_original)) {
                    try {
                        $avatarsName = $account_details['first_name'] . $account_details['last_name'] . time() . '.jpg';
                        copy($providerUser->avatar_original, storage_path('app/' . $avatarsName));
                        File::copy(storage_path('app/' . $avatarsName), public_path(User::PHOTO_PATH . $avatarsName));
                        $account_details['avatar'] = $avatarsName;
                        $account_details['avatar_type'] = User::AVATAR_TYPE__IMAGE;
                    } catch (Exception $e) {

                    }
                }

                if($account_details['email'] == null){
                    $account_details['email'] =  $account_details['first_name'].$account_details['last_name']."@twitter.com";
                }

                $user = User::create($account_details);

            }

            // google plus
            if ($providersName == 'google')
            {
                $fullname = explode(' ', $providerUser->name);
                $account_details['first_name'] = isset($fullname[0]) ? $fullname[0] : '';
                $account_details['last_name'] = isset($fullname[1]) ? $fullname[1] : '';
                $account_details['name'] = $providerUser->name;
                if (isset($providerUser->avatar_original)) {
                    try {
                        $avatarsName = $account_details['first_name'] . $account_details['last_name'] . time() . '.jpg';
                        copy($providerUser->avatar_original, storage_path('app/' . $avatarsName));
                        File::copy(storage_path('app/' . $avatarsName), public_path(User::PHOTO_PATH . $avatarsName));
                        $account_details['avatar'] = $avatarsName;
                        $account_details['avatar_type'] = User::AVATAR_TYPE__IMAGE;
                    } catch (Exception $e) {

                    }
                }
                if($account_details['email'] == null){
                    $account_details['email'] =  $account_details['first_name'].$account_details['last_name']."@google.com";
                }

                $user = User::create($account_details);

            }

            $account->user()->associate($user);
            $account->save();

            //save users friends
            // facebook
          /*  if (isset($providerUser->profileUrl)) {
                Friends::where('friends1', $providerUser->id)->orwhere('friends2', $providerUser->id)->delete();
                if (isset($providerUser['context']['mutual_friends'])) {
                    $friends = $providerUser['context']['mutual_friends']['data'];
                    $i = 0;
                    foreach ($friends as $friend){
                        $mutualFrId[$i]['friends1'] =  $providerUser->id;
                        $mutualFrId[$i]['friends2'] =  $friend['id'];
                        $i++;
                    }
                    Friends::insert($mutualFrId);
                }
            }
*/
            Auth::login($user);
            SocialAccount::whereUserId($account->user->id)->update(['longToken' => $longToken]);

            return false;

        }



    }

}