<?php
declare(strict_types=1);

class AuthController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function loginForm(): void
    {
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['title' => 'Register']);
    }

    public function register(): void
    {
        verify_csrf();
        $data = $this->clean($_POST);
        $errors = $this->validateRegister($data);

        if ($this->users->findByEmail($data['email'] ?? '')) {
            $errors['email'] = 'Email already exists.';
        }

        if ($errors) {
            $this->view('auth/register', ['title' => 'Register', 'errors' => $errors, 'old' => $data]);
            return;
        }

        $this->users->create($data);
        $_SESSION['flash'] = 'Registration complete. Please login.';
        $this->redirect('login');
    }

    public function login(): void
    {
        verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->view('auth/login', ['title' => 'Login', 'error' => 'Invalid email or password.', 'old' => ['email' => $email]]);
            return;
        }

        Auth::login($user);
        if (!empty($_POST['remember'])) {
            setcookie('remember_email', $user['email'], time() + 604800, '/');
        }
        $this->redirect('');
    }

    public function profile(): void
    {
        Auth::requireLogin();
        $user = $this->users->find(Auth::id());
        $orders = (new Order())->userOrders(Auth::id());
        $this->view('auth/profile', ['title' => 'Profile', 'user' => $user, 'orders' => $orders]);
    }

    public function updateProfile(): void
    {
        Auth::requireLogin();
        verify_csrf();
        $user = $this->users->find(Auth::id());
        $data = $this->clean($_POST);
        $errors = $this->validateProfile($data);

        $emailOwner = $this->users->findByEmail($data['email'] ?? '');
        if ($emailOwner && (int) $emailOwner['id'] !== Auth::id()) {
            $errors['email'] = 'Email is used by another account.';
        }

        if (!empty($data['password'])) {
            if (!password_verify($_POST['current_password'] ?? '', $user['password_hash'])) {
                $errors['current_password'] = 'Current password is wrong.';
            }
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'New password must be at least 8 characters.';
            }
        }

        $data['profile_picture'] = $user['profile_picture'];
        if (!empty($_FILES['profile_picture']['name'])) {
            $upload = $this->uploadImage($_FILES['profile_picture'], 'profiles');
            if (isset($upload['error'])) {
                $errors['profile_picture'] = $upload['error'];
            } else {
                $data['profile_picture'] = $upload['path'];
            }
        }