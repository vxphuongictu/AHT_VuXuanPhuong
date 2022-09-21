<div class="wp_dummy_content_generator-success-msg" style="display: none;"></div>
<div class="wp_dummy_content_generator-error-msg" style="display: none;"></div>
<form method="post" id="wp_dummy_content_generatorGenUserForm" class="wp_dummy_content_generatorCol-9">
    <input type="hidden" name="action" value="wp_dummy_content_generatorAjaxGenUsers" />
    <input type="hidden" name="remaining_users" class="remaining_users" value="" />
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Choose User Role</th>
            <td>
                <select name="wp_dummy_content_generator-userRole">
                    <?php wp_dropdown_roles( 'subscriber' ); ?>
                </select>
                <p class="description">Choose the user role for which you want to generate the dummy users.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Number of Users</th>
            <td>
                <input type="number" name="wp_dummy_content_generator-user_count" class="wp_dummy_content_generator-user_count" placeholder="Number of Users" value="10" max="200" min="1" />
                <p class="description">Enter the number of users you want to generate. Please use at a max of 500</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Generate Bio</th>
            <td>
                <input type="checkbox" name="wp_dummy_content_generator-bio" />
                <p class="description">Check this checkbox if you want to generate the Bio for these users. If you are not sure about this then you can keep it unchecked.</p>
            </td>
        </tr>
    </table>
    <input class="wp_dummy_content_generator-btn btnFade wp_dummy_content_generator-btnBlueGreen wp_dummy_content_generatorGenerateUsers" type="submit" name="wp_dummy_content_generatorGenerateUsers" value="Generate Users">
</form>
<div class="wrapper dcsLoader wp_dummy_content_generatorCol-3" style="display: none;">
    <div class="wp_dummy_content_generatorLoaderWrpper c100 p0 blue">
        <span class="wp_dummy_content_generatorLoaderPer">0%</span>
        <div class="slice">
            <div class="bar"></div>
            <div class="fill"></div>
        </div>
    </div>
</div>