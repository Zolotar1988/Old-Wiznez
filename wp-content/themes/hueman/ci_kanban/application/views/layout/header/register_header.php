<div id="loginRegister-header">

    <h1>Find interns. Join a startup</h1>
    <h2>Bridging the gap between startups and interns</h2>

    <section id="register_area" class="wrap">
        <div class="accTypeSel cf">
            <a href="#" onclick="account.register.selectProfileType('startup'); return false;" class="btn transparent">I'm a startup</a>
            <a href="#" onclick="account.register.selectProfileType('intern'); return false;" class="btn transparent">I'm an intern</a>
        </div>

        <form method="POST" action="" id="register_form" style="display: none">
            <input type="hidden" id="register_profile_type">
            <input type="text" id="register_name" placeholder="Full name">
            <input type="text" id="register_username" placeholder="Email">
            <input type="password" id="register_password" placeholder="Password">
            <input type="button" value="Sign up" class="btn cta" onclick="account.register.submit()">
            <div class="form_goBack"><a href="#" onclick="account.register.clearProfileTypeSelection()">&#8592 Go back</a></div>
            <div class="agreeToTerms">By clicking Sign up, you are indicating that you have read and agree to the <a href="/docs/TermsandAgreements.html">Terms of Service</a></div>
        </form>
    </section>

</div>