@extends('backend.layouts.app')
@section('title', 'Account')
@section('content')

    <aside id="right_content" class="col-12 col-lg-9">
        <div class="inner">
            <div class="container xs">

                <div class="hgroup align-center seperator_center">
                    <h2>Trainings</h2>
                    <p>Please get trained in the following categories and pass the quiz to start taking your first
                        order.</p>
                </div>
            </div>
            <div class="container">
                <div class="trainings_list">
                    <div class="row">
                        <?php $i = 1;
                        ?>
                        @foreach($categories as $category)
                            <?php $disable_class='';
                            $disable_button = '';?>
                            @if($category->type == 'special')
                                <?php
                                $disable_class=  'disabled_box';
                                $disable_button = 'inactiveLink';
                                $sprint_count = \App\Models\Sprint::where('joey_id',auth()->user()->id)->get();
                                if (count($sprint_count) >= $category->order_count){
                                    $disable_class=  '';
                                    $disable_button = '';
                                }?>
                            @endif
                            <?php
                            $show_quiz=false;
                            $training_compulsory = \App\Models\Trainings::where('order_category_id',$category->id)->where('is_compulsory',1)->pluck('id');
                            $joey_training_seen = \App\Models\JoeyTrainingSeen::whereIn('training_id',$training_compulsory)->where('joey_id',auth()->user()->id)->get()->count();
                            if(count($training_compulsory) >= 1 && count($training_compulsory) == $joey_training_seen)
                            {
                                $show_quiz = true;
                            }
                            ?>
                            <div class="col-md-6">
                                <div class="training_box {{$disable_class}}">
                                    <div class="row1">
                                        <div class="info_wrap">
                                            <div class="index"><span
                                                        class="flexbox flex-center jc-center f18">{{$i}}</span>
                                            </div>
                                            <div class="info">
                                                <h4 class="h5">{{$category->name}}</h4>
                                                <div class="tutorials_count bc1-light">{{$category->trainings->count()}}
                                                    {{ ($category->trainings->count() == 1) ? 'Tutorial' : 'Tutorials' }}
                                                </div>
                                            </div>
                                            <div class="status_training">
                                                <?php $result = \App\Models\JoeyAttemptedQuiz::where('category_id', $category->id)->where('joey_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();?>
                                                @if($result)
                                                    @if($result->is_passed == 1)
                                                        <div class="status_passed btn btn-bc1lightest no-shadow btn-sm cursor-default no-hover">
                                                            <i class="icofont-check icon-scale-16"></i> Passed
                                                        </div>
                                                    @else
                                                        @if($show_quiz)
                                                            <a href="{{url('training-quiz/'.base64_encode($category->id))}}"
                                                               class="status_passed btn btn-basecolor1 no-shadow btn-sm ">Start Quiz</a>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($show_quiz)
                                                        <a href="{{url('training-quiz/'.base64_encode($category->id))}}"
                                                           class="status_passed btn btn-basecolor1 no-shadow btn-sm ">Start Quiz</a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row_actions">
                                        <ul class="no-list flexbox flex-center">
                                            <?php
                                            $training_documents = \App\Models\Trainings::where('order_category_id',$category->id)
                                                ->whereIn('type',['application/pdf','document','image/png','image/jpeg'])->get();

                                            $training_videos = \App\Models\Trainings::where('order_category_id',$category->id)->where('type','video/mp4')->get();
                                            ?>
                                            <li><a href="{{url('training-videos/'.base64_encode($category->id))}}"
                                                   class="btn btn-bc1lightest no-radius full-w no-shadow {{ (count($training_videos) > 0) ? '' : 'inactiveLink' }}"><span
                                                            class="sprite-25-icon-video d-none d-sm-block"></span>Watch
                                                    Videos</a></li>
                                            <li><a href="{{url('training-documents/'.base64_encode($category->id))}}"
                                                   class="btn btn-bc1lightest no-radius full-w no-shadow {{ (count($training_documents) > 0) ? '' : 'inactiveLink' }}"><span
                                                            class="sprite-25-icon-book d-none d-sm-block"></span>Read
                                                    Documents</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php $i = $i + 1; ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
@stop

@section('footer-js')
@stop
