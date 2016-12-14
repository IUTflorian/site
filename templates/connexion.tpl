 <div class="span8">
        <!-- notifications -->
        {* COMMENTAIRES *}
            {if isset($statut_connexion) AND $statut_connexion == FALSE}
            
            <div class="alert alert-error " role="alert">
                <strong>Echec</strong> La connexion n'a pas réussi.
            </div>
            {/if}
        
        
<form action="connexion.php" method="post" enctype="multipart/form-data" id="form_connexion" name="form_connexion" >
<div class="clearfix">
                <label for="texte">Email</label>
                <div class="input"><textarea name="email" id="email"></textarea></div>
            </div>
            <div class="clearfix">
                <label for="texte">Mot de passe</label>
                <div class="input"><input type = "password" name="mdp" id="mdp"></textarea></div>
            </div>
            <div class="form-actions">
                <input type="submit" name="connexion" value="Se connecter" class="btn btn-large btn-primary">
            </div>
        </form>