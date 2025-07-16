<p>{{ settings()->get('expert_comptable_paie_name') }},</p>
<p>Veuillez trouver ci-joint la DPAE de {{ $salarie->name }} {{ $salarie->firstname }}, avec les documents necessaire.</p>
<p>Cordialement,</p>
<p>
    {{ App\Models\Core\Company::first()->name }}
</p>
