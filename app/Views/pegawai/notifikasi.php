<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="notification-box ml-15 d-none d-md-flex">
    <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z" fill="" />
            <path d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z" fill="" />
        </svg>
        <span><?= count($notifikasi) ?></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification">
        <?php if (!empty($notifikasi)) : ?>
            <?php foreach ($notifikasi as $notif) : ?>
                <li>
                    <a href="#0">
                        <div class="image">
                            <img src="assets/images/lead/lead-6.png" alt="" />
                        </div>
                        <div class="content">
                            <h6>
                                <?= $notif['judul'] ?>
                            </h6>
                            <p>
                                <?= $notif['isi'] ?>
                            </p>
                            <span><?= time_ago($notif['created_at']) ?></span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No notifications</li>
        <?php endif; ?>
    </ul>
</div>

<?= $this->endSection() ?>
