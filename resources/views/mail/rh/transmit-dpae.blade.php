<p>{{ settings()->get('expert_comptable_paie_name') }},</p>
<p>Veuillez trouver ci-joint la DPAE de {{ $salarie->nom }} {{ $salarie->prenom }}, avec les documents necessaires.</p>
<p>Cordialement,</p>
<p>
    {{ App\Models\Core\Company::first()->name }}
</p>
