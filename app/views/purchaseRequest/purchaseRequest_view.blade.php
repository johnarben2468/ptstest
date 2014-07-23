@extends('layouts.dashboard')

@section('header')
	<style type="text/css">
	td{

		padding:5px 10px;
		vertical-align:top;
		word-break:break-word;
	}
    
    @media print /*FOR PRINT LAYOUT*/
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
            
        table, tr, td, th, p, h1, h2, h3, h4, h5
        {
            border-collapse: collapse !important;
            padding : 0px !important;
            font-size : 86% !important;
            height : 4px !important;
        }

        .panel, .panel-heading
        {
            margin: 0px !important;
            /*padding : 5px !important;*/
        }
    }
	</style>


	{{ HTML::script('js/lightbox.min.js') }} 
	{{ HTML::style('css/lightbox.css')}}

@stop

@section('content')		

	@if(Session::get('notice'))
		<div class="alert alert-success"> {{ Session::get('notice') }}</div> 
	@endif

	<h2 class="pull-left">Purchase Request Details </h2>


	<div class="btn-group pull-right options">
            <button class="btn btn-info no-print" onclick="window.print()">
                <span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp;Print
            </button>
	<?php 
		$cuser=Auth::User()->id;
		if (Entrust::hasRole('Administrator'))
		{
			if($purchase->status!="Cancelled")
			{
	?>
				<a href="../edit/{{$purchase->id}}" class="btn btn-success no-print">
					<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
				</a>
	<?php
			}
		}

		else if (Entrust::hasRole('Procurement Personnel'))
		{
			if($purchase->created_by==$cuser)
			{
				if($purchase->status!="Cancelled")
				{
	?>
					<a href="../edit/{{$purchase->id}}" class="btn btn-success no-print">
						<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
					</a>


	<?php 
				} 
			}
		}
	?>

		<button type="button" class="btn btn-default no-print" onclick="window.location.href='../../purchaseRequest/view'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>
		
	</div>

	<hr class="clear no-print" />

	@if($purchase->status == "Cancelled")
		<div class="alert alert-danger no-print"> Reason: {{ strip_tags($purchase->reason) }}</div> 
	@endif
	
	<div class="panel panel-success">
		<div class="panel-body">
			<table border="1" class="proc-details">
				<tr>
					<td class="proc-headers" colspan="3"><h4 style="line-height: 25px;">
						<?php $workName = DB::table('workflow')->where('id',$wfName->work_id)->first(); ?>
						{{{ strtoupper($workName->workFlowName) }}}

						<span class="no-print label {{($purchase->status == 'New') ? 'label-primary' : (($purchase->status == 'Active') ? 'label-success' : (($purchase->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">
							{{ $purchase->status; }}
						</span>
						</h4>
					</td>

					<td colspan="1" width="30%">
						<span class="bac-ctrl-no">BAC CTRL. NO.:</span><br/>
						<h4 align="center" class="ctrl-no">{{ $purchase->controlNo }}</h4>
					</td>
				</tr>

				<tr>
					<td class="proc-headers" width="20%"><h5>REQUISITIONER</h5></td>
					<td class="proc-data">
						<?php $user = User::find($purchase->requisitioner) ?>
						{{ $user->lastname . ", " . $user->firstname }}
					</td>
                    <td class="proc-data">DATE REQUESTED</td>
                    <td class="proc-data">{{ $purchase->dateRequested }}</td>
				</tr>

				<tr>
					<td class="proc-headers" width="20%"><h5>PROJECT / PURPOSE</h5></td>
                    <td class="proc-data">{{ $purchase->projectPurpose }}</td>
                    <td class="proc-data">DATE RECEIVED</td>
					<td class="proc-data">{{ $purchase->dateReceived }}</td>
				</tr>

				<tr>
					<td class="proc-headers" width="20%"><h5>SOURCE OF FUNDS</h5></td>
					<td class="proc-data" colspan="3">{{ $purchase->sourceOfFund }}</td>
				</tr>

				<tr>
					<td class="proc-headers" width="20%"><h5>ABC AMOUNT</h5></td>
					<td class="proc-data" colspan="3">{{ $purchase->amount }}</td>
				</tr>

			</table>
		</div>
	</div>

	<!-- Section 1  -->
            <?php 
            //Cursor Component
                //Count Cursor
                $id=$purchase->id;
                $docs=DB::table('document')->where('pr_id', '=',$id )->first();
                $workflow=DB::table('workflow')->get();
                $taskch= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->count(); 
                //Get Cursor Value
                $taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->first(); 
                
                //Queries
                $workflow= Workflow::find($docs->work_id);
                $section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();
                $taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();
                
                $sectioncheck=0;
                $prdays=0;
      
                foreach($section as $sections)
                {
                    $sectiondays=0;
                    $task= Task::where('section_id', $sections->section_order_id)->where('wf_id', $workflow->id)->orderBy('order_id', 'ASC')->get();
                    echo "<div class='panel panel-success'><div class='panel-heading'><h3 class='panel-title'>".$sections->section_order_id.". ".$sections->sectionName."</h3></div>";
                 
                    echo "<div class='panel-body'>";
                    echo "<table border='1' class='workflow-table'>";

                    //Addon Display
                    $otherc=OtherDetails::where('section_id', $sections->id)->count();

                    if($otherc!=0)
                    {
                        $otherd= OtherDetails::where('section_id', $sections->id)->get();
                        foreach ($otherd as $otherdetails) 
                        {
                            if($otherdetails->label!="Total Days for BAC Documents Preparation"&&$otherdetails->label!="Compliance")
                            {

                            echo "<tr><td width='30%'>".$otherdetails->label."</td>";
                            $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->count();
                            $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->first();  
                             ?>  
                            @if ($valuesc==0) 
                                {{Form::open(['url'=>'insertaddon'], 'POST')}}
                                    <input type="hidden" name="otherDetails_id" value="{{$otherdetails->id}}">
                                    <input type="hidden" name="purchase_request_id" value="{{$purchaseToEdit->id}}">
                                    <td colspan="3"><input name ="value" type="text" class="form-control"></td>
                                    <td align="center"><button class ="btn btn-primary">Save</button></td>
                                {{Form::close()}}
                            @else 
                                <td width='48.5%' colspan='3'>{{$values->value}}</td>
                    
                                {{Form::open(['url'=>'editaddon', 'POST'])}}
                                    <input type='hidden' name='values_id' value="{{$values->id}}">
                                    <td colspan='2' align='center'>
                                        <button class ='btn btn-default'>Edit</button>
                                    </td>
                                {{Form::close()}}
                            @endif
                   
                        
                            </tr>
                             <?php
                            }
                        }   
                    }
                    //End of Addon Display
                    $previousTaskType="0";
                  
                foreach ($task as $tasks) 
                {
                    

                    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();
                    if($taskp->status=="Lock")
                        continue;

                    if ($previousTaskType!="normal"&&$tasks->taskType=="normal")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' width='18%'>By:</th>";
                        echo "<th class='workflow-th' width='18%'>Date:</th>";
                        echo "<th class='workflow-th' width='12.5%'>Days of Action</th>";
                        echo "<th class='workflow-th'>Remarks</th></tr>";
                    }
                    if ($previousTaskType!="datebyremark"&&$tasks->taskType=="datebyremark")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' >Date:</th>";
                        echo "<th class='workflow-th' >By:</th>";
                        echo "<th class='workflow-th' colspan='2'>Remarks</th></tr>";
                    }
                    if ($previousTaskType!="dateby"&&$tasks->taskType=="dateby")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' colspan='2'>Date:</th>";
                        echo "<th class='workflow-th' colspan='2'>By:</th></tr>";
                    }
                    $previousTaskType=$tasks->taskType;

                    //Displayer 
                    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

                   
                        
                 
                    if(1==1)
                    {
                        
                        echo "<tr>";

                        if ($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq")
                        {
                            echo "<td>";
                            echo $tasks->taskName."</td>";
                        }
                        
                        //Task Display
                        
                      
                        ?>

                        @if ($tasks->taskType=="normal")
                            <td>
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
                                {
                                    $assign_user=User::find($taskp->assignee_id);
                                    echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?>
                            </td>

                            <td >
                            <?php 
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td>
                            <?php 
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $taskp->daysOfAction; 
                            ?>
                            </td>
                            <td>
                            <?php 
                                $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                                echo $dremarks; 
                            ?>
                            </td>
                        @endif
                        @if($tasks->taskType=="certification")
                            <td colspan="2">
                                <input type="radio" name="displayradio" value="yes" 
                                <?php if($taskp->custom1=="yes") echo " checked";?> 
                                disabled > Yes 
                                <input type="radio" name ="displayradio" value="no" 
                                <?php if($taskp->custom1=="no") echo " checked";?>
                                disabled> No
                            </td>
                            <td colspan="2">
                                By:         
                                {{$taskp->custom2;}}
                            </td>
                        @endif
                        @if($tasks->taskType=="posting")
                            <td colspan="2">
                                Reference No. :          
                                {{$taskp->custom1;}}
                            </td>
                             <td>
                                Date:           
                                {{$taskp->custom2;}}
                            </td>
                            <td>
                                By:          
                                {{$taskp->custom3;}}
                            </td>
                        @endif
                        @if($tasks->taskType == "supplier")
                                <td class="edit-pr-input" colspan="2">
                                    {{$taskp->custom1}}
                                </td>
                                
                                <td class="edit-pr-input" colspan="2">
                                    Amount: 
                                    {{$taskp->custom2}}
                                </td>     
                        @endif
                        @if($tasks->taskType=="cheque")
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE AMT:
                                    {{$taskp->custom1}}
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE NUM:
                                    {{$taskp->custom2}}
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE DATE:
                                    {{$taskp->custom3}}
                                </td>
                        @endif
                        @if($tasks->taskType=="published")
                                    <td>
                                    {{$tasks->taskName}}
                                    <br>
                                    {{$taskp->custom1}}
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    </td>
                                    <td>
                                    End Date
                                    <br>
                                    {{$taskp->custom2}}
                                    </td>
                                    <td >
                                    Posted By
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="documents")
                                    <td>
                                    {{$tasks->taskName}}
                                    <br>
                                    {{$taskp->custom1}}
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    </td>
                                    <td>
                                    Date of Bidding
                                    <br>
                                    {{$taskp->custom2}}
                                    </td>
                                    <td >
                                    Checked By
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="evaluations")
                                    <td>
                                    {{$taskp->custom1}}
                                    </td>
                                    
                                    <td >
                                    No. of Days Accomplished
                                    </td>
                                    <td class="edit-pr-input" colspan="3">  
                                    {{$taskp->custom2}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="conference")
                                    <td colspan="4">
                                    {{$taskp->custom1}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="contract")
                                    <td>
                                    {{$tasks->taskName}}
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    {{$taskp->custom1}}
                                    </td>
                                    <td >
                                    No. of Days Accomplished
                                    </td>
                                    <td class="edit-pr-input">  
                                    {{$taskp->custom2}}
                                    </td>
                                    <td>
                                    Contract Agreement
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="meeting")
                                    <td>
                                    {{$tasks->taskName}}
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    {{$taskp->custom1}}
                                    </td>
                                    <td >
                                    No. of Days Accomplished
                                    </td>
                                    <td class="edit-pr-input">  
                                    {{$taskp->custom2}}
                                    </td>
                                    <td>
                                    Minutes of Meeting
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="rfq")
                                    <td>
                                    {{$tasks->taskName}} {{$taskp->custom1}}
                                    </td>
                                    <td>
                                    Date of RF (Within PGEPS 7 Days)
                                    </td>
                                    <td>
                                    {{$taskp->custom2}}
                                    </td>
                                    <td>
                                    By
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td>
                        @endif
                        @if($tasks->taskType=="dateby") 
                            <td colspan="2">
                            <?php 
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td colspan="2">
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
                                {
                                    $assign_user=User::find($taskp->assignee_id);
                                    echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?>
                            </td>
                        @endif
                        @if($tasks->taskType=="datebyremark")
                           <td >
                            <?php 
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td>
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
                                {
                                    $assign_user=User::find($taskp->assignee_id);
                                    echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?>
                            </td>
                            <td colspan="2">
                            <?php 
                                $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                                echo $dremarks; 
                            ?>
                            </td>
                        @endif

                        <?php 
                        //End Task Display
                        $sectiondays=$sectiondays+$taskp->daysOfAction;
                        $prdays=$prdays+$taskp->daysOfAction;

                        
                    }   
                        echo "</tr>";
                }
            //Addon Display
                    $otherc=OtherDetails::where('section_id', $sections->id)->count();

                    if($otherc!=0)
                    {
                        $otherd= OtherDetails::where('section_id', $sections->id)->get();
                        foreach ($otherd as $otherdetails) 
                        {
                            if($otherdetails->label=="Total Days for BAC Documents Preparation"||$otherdetails->label=="Compliance")
                            {

                            echo "<tr><td width='30%'>".$otherdetails->label."</td>";
                            $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->count();
                            $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->first();    
                             ?>
                            @if ($valuesc==0) 
                                {{Form::open(['url'=>'insertaddon'], 'POST')}}
                                    <input type="hidden" name="otherDetails_id" value="{{$otherdetails->id}}">
                                    <input type="hidden" name="purchase_request_id" value="{{$purchaseToEdit->id}}">
                                    <td colspan="3"><input name ="value" type="text" class="form-control" maxlength="100"></td>
                                    <td align="center"><button class ="btn btn-primary">Save</button></td>
                                {{Form::close()}}
                            @else 
                                <td width='48.5%' colspan='3'>{{$values->value}}</td>

                                {{Form::open(['url'=>'editaddon', 'POST'])}}
                                    
                                    <input type='hidden' name='values_id' value="{{$values->id}}">
                                    <td colspan='2' align='center'>
                                        <button class ='btn btn-default'>Edit</button>
                                    </td>
                
                                {{Form::close()}}
                                </tr>
                            @endif
                    <?php
                           
                            }   
                        
                        }
                    //End of Addon Display

                    }

                    ?>

                    @if($workflow->workFlowName!="Direct Contracting")
                    <tr>
                            <td>TOTAL NO. OF DAYS</td>
                            <td></td>
                            <td></td>
                            <td>{{$sectiondays}}</td>
                            <td></td>
                    </tr>
                    @endif
                    </table></div></div>
                    
                    <?php
                }
                echo "<div class='panel panel-success'><div class='panel-body'>
                        <table border='1' class='proc-details'>
                            <tr>
                                <td width='66%'><h4 style='margin-left: 10px'>TOTAL NO. OF DAYS FROM PR TO PAYMENT: </h4></td>
                                <td><h4 style='margin-left: 50px;'>".$prdays."</h4></td>
                            </tr>
                        </table>
                    </div></div>"; 
            //end section
            ?>
	
	<?php
		function data_uri($image, $mime) 
		{  
			$base64   = base64_encode($image); 
			return ('data:' . $mime . ';base64,' . $base64);
		}
	?>

	<!-- images section -->
	
	<div id="img-section" class="no-print">

		<?php
			$docs= Document::where('pr_id', $purchase->id)->first();
			$attachmentc = DB::table('attachments')->where('doc_id', $docs->id)->count();
			if ($attachmentc!=0)
				echo "<h3>"."Attachments"."</h3>";

			$luser=Auth::user()->id;
			$count= Count::where('doc_id','=', $docs->id)->where('user_id','=', $luser )->delete();


			$attachments = DB::table('attachments')->where('doc_id', $docs->id)->get();	
			$srclink="uploads\\";
		?>

		@foreach ($attachments as $attachment) 
			<div class="image-container">
				<a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
					<img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" />
				</a>

			</div>
		@endforeach

	</div>
	
	{{ Session::forget('notice'); }}
@stop