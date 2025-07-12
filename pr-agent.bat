@echo off
setlocal enabledelayedexpansion

:: Configuration
set PR_NUMBER=%1
set OLLAMA_MODEL=ai/llama3.1
set OLLAMA_URL=http://localhost:12434/engines/llama.cpp/v1/chat/completions

:: Vérification des dépendances
for %%c in (git curl jq gh) do (
    where %%c >nul 2>&1 || (
        echo ❌ Commande manquante : %%c
        exit /b 1
    )
)

if "%PR_NUMBER%"=="" (
    echo ❌ Utilisation : %0 ^<numero_pr^>
    exit /b 1
)

:: Récupération des commits
echo 📥 Récupération des commits de la PR #%PR_NUMBER%...
for /f "delims=" %%a in ('gh pr view %PR_NUMBER% --json commits --jq ".commits[].messageHeadline"') do set "COMMITS=%%a"

:: Génération du prompt
set "PROMPT=Voici une liste de commits d'une Pull Request : [%COMMITS%], Génère une description claire, professionnelle, concise et orientée utilisateur de cette PR. Écris en français. Format Markdown. Sans Résonnement. Seul les commit (feat, fix, release, breaking) doivent être pris en compte."

echo 🧠 Envoi des commits à Ollama (%OLLAMA_MODEL%)...
for /f "delims=" %%d in ('curl --request POST --url %OLLAMA_URL% --header "Content-Type: application/json" --data "{\"model\":\"%OLLAMA_MODEL%\",\"messages\":[{\"role\":\"user\",\"content\":\"%PROMPT%\"}]}" ^| jq -r ".choices[0].message.content"') do set "DESCRIPTION=%%d"

echo.
echo 📝 Nouvelle description générée :
echo ---------------------------------
echo !DESCRIPTION!
echo ---------------------------------

set /p CONFIRM="Souhaitez-vous mettre à jour la description de la PR ? (o/n) : "
if /i not "%CONFIRM%"=="o" (
    echo ❌ Opération annulée.
    exit /b 0
)

echo 🚀 Mise à jour de la PR sur GitHub...
gh pr edit %PR_NUMBER% --body "%DESCRIPTION%"

echo ✅ PR #%PR_NUMBER% mise à jour avec succès.
endlocal
