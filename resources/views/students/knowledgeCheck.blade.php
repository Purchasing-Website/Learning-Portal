@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <h1>{{ $mode }}</h1>

    <h2 class="mb-3">{{ $knowledgeCheck->title }}</h2>
    <p class="text-muted">{{ $knowledgeCheck->description }}</p>

    <form action="{{ route('student.knowledge.submit', [$knowledgeCheck->id,$mode]) }}" method="POST">
        @csrf

        @foreach($knowledgeCheck->questions as $q)
            <div class="card mb-4">
                <div class="card-header">
                    <b>Question {{ $loop->iteration }}:</b> {{ $q->question }}
                </div>

                <div class="card-body">

                    @if($q->questiontype === 'true_false')
                        {{-- TRUE / FALSE --}}
                        <div class="form-check mb-2">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   class="form-check-input"
                                   value="true">
                            <label class="form-check-label">True</label>
                        </div>

                        <div class="form-check mb-2">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   class="form-check-input"
                                   value="false">
                            <label class="form-check-label">False</label>
                        </div>

                    @else
                        {{-- SINGLE and MULTIPLE --}}
                        @foreach($q->options as $option)
                            <div class="form-check mb-2">

                                @if($q->questiontype === 'single')
                                    <input type="radio"
                                           class="form-check-input"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ $option->id }}">
                                @elseif($q->questiontype === 'multiple')
                                    <input type="checkbox"
                                           class="form-check-input"
                                           name="answers[{{ $q->id }}][]"
                                           value="{{ $option->id }}">
                                @endif

                                <label class="form-check-label">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                        @endforeach

                    @endif

                </div>
            </div>
        @endforeach

        <button id="submitKnowledgeCheck" class="btn btn-primary btn-lg w-100" type="submit">Submit Answers</button>
    </form>

</div>

@endsection
