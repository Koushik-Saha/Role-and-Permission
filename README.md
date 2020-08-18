# Create users and set role and permission 
> How to install
 - Clone the repository
 - Set up database credentials in .env file
 - Run `php artisan migrate` to migrate the database
 - Run `composer install`
 - Run `php artisan serve`
 
> What you get
 - Dashboard 
 - Login (Without register login data is on userSeeder)
 - Add Manpower (Create User)
 - Add Designation (Create Role)
 - Staff List (All User List)
 - Set Permission to user

#Role Table
 - role_id
 - role_name
 - role_slug
#User Table
 - name
 - email
 - password
 - username
 - mobile
 - status 
 
 Role Create and Permission it uses https://github.com/cviebrock/eloquent-sluggable library where it manage role of the user and permission that what user can do.
 
 Example : `Auth::user()->isManager()` or `Auth::user()->isAdmin()`
 
 Relationship : 
 
 #Role Table
 ```
  public function users()
  {
      return $this->hasMany(User::class, 'role_id', 'role_id');
  }
    
    public function sluggable()
    {
        return [
            'role_slug' => [
                'source' => 'role_name'
            ]
        ];
    }
 ```
 
 #User Table
 
 ```
 public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin() 
    {
        return $this->role->role_slug === 'administrator';
    }

    public function isManager() 
    {
        return $this->role->role_slug === 'manager';
    }
    
 ```
 
