<?php

/** @var \CodeIgniter\View\View $this */ ?>

<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<h2>🔔 Notifikasi</h2>

<!-- Tombol Hapus Semua -->
<a href="<?= base_url('admin/notifikasi/clear') ?>" class="btn btn-danger mb-3" onclick="return confirm('Yakin mau hapus semua notifikasi?')">
    🗑 Hapus Semua
</a>

<div class="notification-box">
    <button class="dropdown-toggle btn btn-outline-secondary" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z" fill="" />
            <path d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z" fill="" />
        </svg>
        <span class="badge bg-danger"><?= is_array($notifikasi) ? count($notifikasi) : 0 ?></span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-2 shadow-sm" aria-labelledby="notification" style="max-height: 400px; overflow-y: auto;">
        <?php if (!empty($notifikasi)) : ?>
            <?php foreach ($notifikasi as $notif) : ?>
                <li class="mb-2">
                    <a href="<?= base_url('admin/notifikasi/tandai_sudah_dibaca/' . $notif['id']) ?>" class="d-block text-decoration-none">
                        <div class="content p-2 rounded <?= $notif['is_read'] == 0 ? 'bg-light border-start border-primary border-3' : '' ?>">
                            <h6 class="mb-1 fw-bold"><?= esc($notif['judul']) ?></h6>
                            <p class="mb-1 small text-muted"><?= esc($notif['deskripsi']) ?></p>
                            <span class="text-secondary small"><?= time_ago($notif['created_at']) ?></span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li class="text-muted p-2">Tidak ada notifikasi</li>
        <?php endif; ?>
    </ul>
</div>

<?= $this->endSection() ?>