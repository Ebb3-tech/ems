@component('mail::message')
# {{ $isNew ? 'Welcome!' : 'Account Updated' }}

Hello {{ $user->name }},

@if($isNew)
Your account has been created successfully. Here are your details:

@else
Your account information has been updated. Here are the latest details:
@endif

**Name:** {{ $user->name }}  
**Email:** {{ $user->email }}  
**Department:** {{ $user->department->name ?? 'N/A' }}  
**Position:** {{ $user->position ?? 'N/A' }}  
**Role:** 
@switch($user->role)
    @case(1) Employee @break
    @case(2) Call Center @break
    @case(3) Marketing @break
    @case(4) Shop @break
    @case(5) Manager @break
    @default N/A
@endswitch

@if($isNew)
**Password:** (the password you set during registration)
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
