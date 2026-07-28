<p>Bonjour {{ $prenom }} {{ $nom }},</p>

<p>Votre compte a été créé avec succès. Voici vos identifiants de connexion :</p>

<ul>
    <li>Login :{{ $pseudo }} ou {{ $email }}</li>
    <li>Mot de passe : <strong>{{ $password }}</strong></li>
</ul>

<p>Nous vous recommandons de changer ce mot de passe dès votre première connexion.</p>