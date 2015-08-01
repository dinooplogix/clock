<div class="col-md-8 col-md-offset-2">
    <div class="register-form form">
        <?php echo cf_display_front_messages(); ?>
        <div class="col-md-12">
            <h3><?php echo $form_heading; ?></h3>
        </div>


        <form action="" method="POST" name="<?php echo $form_name; ?>">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="first_name" value="<?php echo (isset($first_name)) ? $first_name : ""; ?>" placeholder="First Name" class="form-control" />            
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="last_name" value="<?php echo (isset($last_name)) ? $last_name : ""; ?>" placeholder="Last Name" class="form-control" />
                </div>
            </div>
            <?php $disabled = (isset($disable_username) && $disable_username == true) ? "disabled" : ""; ?>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" <?php echo $disabled; ?> name="user_name" value="<?php echo (isset($user_name)) ? $user_name : ""; ?>" placeholder="Username" class="form-control" />            
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="email" name="user_email" value="<?php echo (isset($user_email)) ? $user_email : ""; ?>" placeholder="Email" class="form-control" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <input type="date" name="dob" placeholder="Date of birth" value="<?php echo (isset($dob)) ? $dob : ""; ?>" class="form-control">
                </div>
            </div>





            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    $radio = array(
                        'client' => "Client",
                        'partner' => "Partner",
                    );
                    $checked = "";
                    foreach ($radio as $key => $value) {
                        if (isset($role)) {
                            $checked = ($key == $role) ? "checked" : "";
                        }
                        echo '<label class="radio-inline"><input type="radio" ' . $checked . ' value="' . $key . '" name="role">' . $value . '</label>';
                    }
                    ?>

                </div>
            </div>




            <!-- PASSWORD -->
            <div class="clearfix"></div>
            <?php $style = ($display_password) ? "" : 'style="display:none"'; ?>

            <div <?php echo $style; ?> >
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="user_pass"  value="<?php echo (isset($user_pass)) ? $user_pass : ""; ?>" placeholder="Password" class="form-control" />            
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="confirm_password"  value="<?php echo (isset($confirm_password)) ? $confirm_password : ""; ?>" placeholder="Confirm Password" class="form-control" />
                    </div>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group">
                    <label class="checkbox-inline"><input type="checkbox" name="mc4wp-subscribe" value="1" />Send me periodic updates from WebFacility</label>
                </div>
            </div>
            <?php wp_nonce_field($form_action, $form_nonce_field); ?>
            <div class="form-group">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
            <div <?php echo $style; ?>>
                <div class="col-md-6 strength">

                </div>
            </div>
        </form>
    </div>
</div>