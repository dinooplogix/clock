<div id="feedback"></div>
<div class="row">
    <?php echo cf_display_front_messages(); ?>
    <?php $search = (isset($_POST['search'])) ? $_POST['search'] : ''; ?>
    <div class="col-lg-4">
        <form action="" method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="<?php echo $search ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                </span>
            </div><!-- /input-group -->
        </form>
    </div><!-- /.col-lg-6 -->
    <div class="col-lg-8">
        <a href="<?php echo get_permalink(get_page_by_path('add-user')); ?>" class="btn btn-default pull-right">+ Add User</a>
    </div>
</div><!-- /.row -->

<br>

<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered">
            <tr>
                <th class="slno">Sl. No:</th>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php foreach ($getThisRoleMembers as $key => $value): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $value['first_name'] . ' ' . $value['last_name']; ?><br>
                        <span class="user-registered">Added on <?php echo date('d/m/Y', strtotime($value['user_registered'])); ?></span></td>
                    <td><?php echo $value['wp_capabilities']; ?></td>
                    <td><?php echo $value['user_email']; ?></td>
                    <td><a href="<?php echo get_site_url() . '/edit-user/?userid=' . $value['ID']; ?>">Edit</a> | 
                        <a href="" class="removeUser" data-user-id="<?php echo $value['ID']; ?>">Remove</a>
                    </td>


                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
