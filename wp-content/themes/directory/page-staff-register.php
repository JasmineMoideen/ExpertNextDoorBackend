<?php
/**
 * Template Name: Staff Registration
 */
get_header('listing');
?>

<div class="customer-registration">
    <h4>Register as Staff</h4>
    <?php
    if (isset($_POST['register_staff'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        $userdata = [
            'user_login' => $username,
            'user_email' => $email,
            'user_pass'  => $password,
            'role'       => 'staff', // custom role
        ];

        $user_id = wp_insert_user($userdata);

        if (!is_wp_error($user_id)) {
            echo '<p style="color:green;">✅ Staff registration successful! <a href="' . wp_login_url() . '">Login here</a>.</p>';
        } else {
            echo '<p style="color:red;">❌ ' . $user_id->get_error_message() . '</p>';
        }
    }


   
    ?>
    <form method="post">
        <input type="text" class="customer-form" name="username" placeholder="Username" required >
        <input type="email" class="customer-form" name="email" placeholder="Email" required >
        <input type="password" class="customer-form" name="password" placeholder="Password" required >
        <input type="submit" class="customer-form customer-form-btn" name="register_staff" value="Register" required>
    </form>

    
</div>

<?php get_footer(); ?>
