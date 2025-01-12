<?php

namespace App\Http\Controllers\JobPositionProfiles;

use App\Models\JobPositionProfiles\JobPositionProfile;
use App\Models\JobPositionProfiles\JobPositionProfileSign;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parameters\Parameter;

class JobPositionProfileSignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, JobPositionProfile $jobPositionProfile)
    {
        if($jobPositionProfile->staff_decree_by_estament_id == NULL ||
            ($jobPositionProfile->roles->count() <= 0 && $jobPositionProfile->objective == NULL) ||
            $jobPositionProfile->working_team == NULL ||
            $jobPositionProfile->jppLiabilities->count() <= 0 ||
            $jobPositionProfile->jppExpertises->count() <= 0){

            return redirect()->route('job_position_profile.edit_expertise_map', $jobPositionProfile);
        }
        else{
            $position = 1;
            for ($i = $jobPositionProfile->organizationalUnit->level; $i >= 2; $i--) {
                if ($i > 2) {
                    $jpp_sing = new JobPositionProfileSign();

                    $jpp_sing                           = new JobPositionProfileSign();
                    $jpp_sing->position                 = $position;
                    $jpp_sing->event_type               = 'leadership';
                    if($i == $jobPositionProfile->organizationalUnit->level){
                        $jpp_sing->organizational_unit_id   = $jobPositionProfile->organizationalUnit->id;
                        $jpp_sing->status = 'pending';
                    }
                    else{
                        $lastSign = JobPositionProfileSign::
                            where('job_position_profile_id', $jobPositionProfile->id)
                            ->latest()
                            ->first();

                        $jpp_sing->organizational_unit_id   = $lastSign->organizationalUnit->father->id;
                    }

                    $jpp_sing->job_position_profile_id = $jobPositionProfile->id;
                    $jpp_sing->save();
                }
                if ($i == 2) {
                    $jpp_review = new JobPositionProfileSign();
                    $jpp_review->position = $position;
                    $jpp_review->event_type = 'review';
                    $jpp_review->organizational_unit_id = Parameter::where('module', 'ou')->where('parameter', 'GestionDesarrolloDelTalento')->first()->value;
                    $jpp_review->job_position_profile_id = $jobPositionProfile->id;
                    $jpp_review->save();

                    $lastSign = JobPositionProfileSign::
                        where('job_position_profile_id', $jobPositionProfile->id)
                        ->where('event_type', 'leadership')
                        ->orderBy('position', 'DESC')
                        ->first();

                    $jpp_esign = new JobPositionProfileSign();
                    $jpp_esign->position = $position + 1;
                    $jpp_esign->event_type = 'subdir o depto';
                    $jpp_esign->organizational_unit_id   = $lastSign->organizationalUnit->father->id;
                    $jpp_esign->job_position_profile_id = $jobPositionProfile->id;
                    $jpp_esign->save();
                }

                $position++;
            }
            
            $jobPositionProfile->save();

            session()->flash('success', 'Estimado Usuario, se ha enviado Exitosamente El Perfil de Cargo');
            return redirect()->route('job_position_profile.index');
        }


        $jpp_review = new JobPositionProfileSign();
        $jpp_review->position = 1;
        $jpp_review->event_type = 'review';
        $jpp_review->status = 'pending';
        $jpp_review->job_position_profile_id = $jobPositionProfile->id;
        $jpp_review->organizational_unit_id = Parameter::where('module', 'ou')->where('parameter', 'GestionDesarrolloDelTalento')->first()->value;
        $jpp_review->save();

        $jpp_esing = new JobPositionProfileSign();
        $jpp_esing->position = 2;
        $jpp_esing->event_type = 'esign';
        $jpp_esing->job_position_profile_id = $jobPositionProfile->id;
        $jpp_esing->organizational_unit_id = $jobPositionProfile->organizationalUnit->id;
        $jpp_esing->save();
        
        $jobPositionProfile->status = 'review';
        $jobPositionProfile->save();

        session()->flash('success', 'Estimado Usuario, se ha enviado Exitosamente El Perfil de Cargo');
        return redirect()->route('job_position_profile.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobPositionProfiles\JobPositionProfileSign  $jobPositionProfileSign
     * @return \Illuminate\Http\Response
     */
    public function show(JobPositionProfileSign $jobPositionProfileSign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobPositionProfiles\JobPositionProfileSign  $jobPositionProfileSign
     * @return \Illuminate\Http\Response
     */
    public function edit(JobPositionProfileSign $jobPositionProfileSign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobPositionProfiles\JobPositionProfileSign  $jobPositionProfileSign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobPositionProfileSign $jobPositionProfileSign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobPositionProfiles\JobPositionProfileSign  $jobPositionProfileSign
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobPositionProfileSign $jobPositionProfileSign)
    {
        //
    }
}
