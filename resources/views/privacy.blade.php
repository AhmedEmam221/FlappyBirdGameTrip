@extends('layouts.app')

@section('title', 'Flappy Challenge - Privacy Policy')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Privacy Policy</h1>
    </header>
    
    <main class="prose max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Information We Collect</h2>
        <p>When you participate in the Flappy Challenge competition, we collect the following information:</p>
        
        <h3 class="text-semibold mb-3">Instagram Username</h3>
        <p>You provide your Instagram username when submitting your score for the leaderboard. This is displayed publicly on the leaderboard along with your score.</p>
        
        <h3 class="text-semibold mb-3">Game Scores</h3>
        <p>We collect your game score, the date and time of submission, and your game session identifier. This information is used to determine the leaderboard rankings and award prizes.</p>
        
        <h3 class="text-semibold mb-3">Instagram Follow Confirmation</h3>
        <p>We record when you click the Instagram follow button and whether you self-report having followed the page. This is used for competition verification purposes only.</p>
        
        <h2 class="text-xl font-bold mb-4">How We Use Your Information</h2>
        <ul class="list-disc list-inside mb-6">
            <li>To display you on the public leaderboard with your score and Instagram username.</li>
            <li>To determine the top 3 winners and award prizes.</li>
            <li>To verify Instagram follows and maintain competition integrity.</li>
            <li>To communicate with winners regarding prize distribution.</li>
        </ul>
        
        <h2 class="text-xl font-bold mb-4">Instagram Follow Claims</h2>
        <p>We are honest that an ordinary website cannot reliably verify an Instagram follow merely by opening a link. 
        When you click our Instagram link, we record the click event, and you self-report that you have followed the page. 
        This confirmation is recorded but not claimed as definitive proof of a follow. The administrator may review entries 
        and approve or reject them based on legitimate means.</p>
        
        <h2 class="text-xl font-bold mb-4">Data Retention</h2>
        <p>Competition entries are retained for the duration of the competition and for a reasonable period after 
        the competition ends to finalize winners and preserve a snapshot of the final standings. After the competition 
        concludes, entries may be anonymized or retained with appropriate privacy protections.</p>
        
        <h2 class="text-xl font-bold mb-4">Cookies</h2>
        <p>We use cookies to enhance your browsing experience, track game sessions, and compile aggregated data 
        about site traffic and interaction. You can choose to accept or decline cookies at the browser level.</p>
        
        <h2 class="text-xl font-bold mb-4">Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the 
        new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p>
        
        <p>You can review the complete competition rules <a href="/rules" class="text-accent hover underline">here</a>.</p>
    </main>
    
    <!-- Back link -->
    <div class="mt-8 text-center">
        <a href="/" class="text-accent hover underline">Back to Home</a>
    </div>
    
</div>