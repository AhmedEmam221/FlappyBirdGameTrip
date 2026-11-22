@extends('layouts.app')

@section('title', 'Flappy Challenge - Competition Rules')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Competition Rules</h1>
    </header>
    
    <main class="prose max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-4">General Rules</h2>
        <ol class="list-decimal list-inside mb-6">
            <li>The Flappy Challenge competition is open to all visitors of the website.</li>
            <li>Players must achieve a valid score by playing the Flappy Bird-style game on the website.</li>
            <li>To have a score considered for the leaderboard, players must submit their Instagram username along with their score.</li>
            <li>Players must follow the official Instagram page configured by the administrator to participate.</li>
            <li>Each player may submit only one score per game session.</li>
            <li>The top 3 players with the highest approved scores at the competition end date will win prizes.</li>
            <li>All decisions by the administrator are final.</li>
        </ol>
        
        <h2 class="text-xl font-bold mb-4">Prize Information</h2>
        <p>The top 3 winners will receive the following prizes:</p>
        <ul class="list-disc list-inside mb-6">
            <li><strong>1st Place:</strong> [Prize description - to be configured by admin]</li>
            <li><strong>2nd Place:</strong> [Prize description - to be configured by admin]</li>
            <li><strong>3rd Place:</strong> [Prize description - to be configured by admin]</li>
        </ul>
        
        <h2 class="text-xl font-bold mb-4">Score Validation</h2>
        <p>Scores are validated server-side. The following will result in score rejection:</p>
        <ul class="list-disc list-inside">
            <li>Negative scores</li>
            <li>Impossibly high scores based on game mechanics and elapsed time</li>
            <li>Duplicate submissions from the same game session</li>
            <li>Scores submitted from expired sessions</li>
            <li>Malformed or duplicate session tokens</li>
        </ul>
        
        <h2 class="text-xl font-bold mb-4">Instagram Verification</h2>
        <p>By submitting a score, players confirm that they have followed the configured Instagram page. 
        The website opens the Instagram page in a new tab when the player clicks the Instagram follow button. 
        The player's follow is self-reported and subject to manual verification by the administrator.</p>
        
        <h2 class="text-xl font-bold mb-4">Tie-Breaking</h2>
        <p>In the event of tied scores, the earliest submission time will determine the higher ranking.</p>
        
        <h2 class="text-xl font-bold mb-4">Eligibility</h2>
        <p>The competition runs from [Start Date] to [End Date], as configured by the administrator. 
        Employees of the competition organizer and their immediate families are not eligible to participate.</p>
        
        <div class="mt-8 p-6 card rounded-lg">
            <h3 class="text-semibold mb-3">Important</h3>
            <p>Do not claim affiliation with or endorsement by Instagram. This competition is not sponsored, endorsed, or administered by, or associated with, Instagram.</p>
        </div>
    </main>
    
    <!-- Back link -->
    <div class="mt-8 text-center">
        <a href="/" class="text-accent hover underline">Back to Home</a>
    </div>
    
</div>