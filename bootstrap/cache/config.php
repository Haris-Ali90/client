<?php return array (
  'admin_permissions' => 
  array (
    'Admin Permissions' => 
    array (
      'Merchant' => 'merchants',
      'Joey' => 'joeys',
      'Finances' => 'finances',
      'Zones' => 'zones',
      'Dispatch' => 'dispatch',
      'Schedules' => 'schedules',
      'Reporting' => 'reporting',
      'Settings' => 'settings',
    ),
  ),
  'app' => 
  array (
    'env' => 'local',
    'debug' => true,
    'url' => '',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'base64:3bDcUE6ar7X9Z4g55dou4w==',
    'cipher' => 'AES-128-CBC',
    'log' => 'single',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      13 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      14 => 'Illuminate\\Queue\\QueueServiceProvider',
      15 => 'Illuminate\\Redis\\RedisServiceProvider',
      16 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      17 => 'Illuminate\\Session\\SessionServiceProvider',
      18 => 'Illuminate\\Translation\\TranslationServiceProvider',
      19 => 'Illuminate\\Validation\\ValidationServiceProvider',
      20 => 'Illuminate\\View\\ViewServiceProvider',
      21 => 'App\\Providers\\AppServiceProvider',
      22 => 'App\\Providers\\AuthServiceProvider',
      23 => 'App\\Providers\\EventServiceProvider',
      24 => 'App\\Providers\\RouteServiceProvider',
      25 => 'Tymon\\JWTAuth\\Providers\\JWTAuthServiceProvider',
      26 => 'Collective\\Html\\HtmlServiceProvider',
      27 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      28 => 'Yajra\\Datatables\\DatatablesServiceProvider',
      29 => 'Laravel\\Socialite\\SocialiteServiceProvider',
      30 => 'Yajra\\DataTables\\DataTablesServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'JWTAuth' => 'Tymon\\JWTAuth\\Facades\\JWTAuth',
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'DataTables' => 'Yajra\\Datatables\\Facades\\Datatables',
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
    'super_admin_role_id' => 1,
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'email' => 'auth.emails.password',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\storage\\framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'claim_permissions' => 
  array (
    'Dashboard' => 
    array (
      'View' => 'dashboard.index',
    ),
    'Roles' => 
    array (
      'Role List' => 'role.index',
      'Add Role' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admins' => 
    array (
      'Sub Admin List' => 'sub-admin.index|sub-admin.data',
      'Add Sub Admin' => 'sub-admin.create|sub-admin.store',
      'Edit' => 'sub-admin.edit|sub-admin.update',
      'Status Change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'sub-admin.show',
      'Delete' => 'sub-admin.destroy',
    ),
    'Client Claims' => 
    array (
      'Add Claims' => 'client-claims.create|client-claims.store|client-claims.validateTrackingId',
      'Pending Claims List' => 'client-claims.pendingList|client-claims.pendingList-data',
      'Approved Claims List' => 'client-claims.approvedList|client-claims.approvedList-data',
      'Not Approved Claims List' => 'client-claims.notApprovedList|client-claims.notApprovedList-data',
    ),
    'JoeyCo Claims' => 
    array (
      'Add Claims' => 'claims.create|claims.store|claims.validateTrackingId',
      'Pending Claims List' => 'claims.pendingList|claims.pendingList-data',
      'Update Status Pending Claims List' => 'claims.statusUpdate-pending|claims.getReasons|claims.statusUpdate',
      'Pending Order Details' => 'pendingClaimsSearchOrder.show|searchorder.show',
      'Approved Claims List' => 'claims.approvedList|claims.approvedList-data',
      'Update Status Approved Claims List' => 'claims.uploadImage-approved|claims.getReasons|claims.uploadImage',
      'Approved Order Details' => 'approvedClaimsSearchOrder.show|searchorder.show',
      'Not Approved Claims List' => 'claims.notApprovedList|claims.notApprovedList-data',
      'Update Status Not Approved Claims List' => 'claims.uploadImage-reject|claims.getReasons|claims.uploadImage',
      'Not Approved Order Details' => 'rejectClaimsSearchOrder.show|searchorder.show',
      'Re-Submitted Claims List' => 'claims.reSubmittedList|claims.reSubmittedList-data',
      'Update Status Re-Submitted Claims List' => 'claims.statusUpdate-re-submitted|claims.getReasons|claims.statusUpdate',
      'Re-Submitted Order Details' => 're-submittedClaimsSearchOrder.show|searchorder.show',
    ),
    'Broker Claims' => 
    array (
      'Claims List' => 'broker-claims.brookerList|broker-claims.brookerList-data',
      'Joeys Claims List' => 'broker-claims.joeyList|broker-claims.joeyList-data',
      'Report' => 'broker-claims.report|broker-claims.report-data',
    ),
    'JoeyCo Review Claims' => 
    array (
      'Broker Claims Review' => 'review.brokerReview|review.brokerReviewData',
      'Joeys Claims Review' => 'review.joeyReview|review.joeyReviewData',
    ),
    'Claim Reasons' => 
    array (
      'Claim Reasons List' => 'claim-reason.data|claim-reason.index',
      'Add Claim Reason' => 'claim-reason.create|claim-reason.store',
      'Edit' => 'claim-reason.edit|claim-reason.update',
    ),
    'Setting' => 
    array (
      'Setting Main Page' => 'dashboard.index',
      'Change Password' => 'users.change-password',
      'Edit Profile' => 'users.edit-profile',
    ),
  ),
  'database' => 
  array (
    'fetch' => 8,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'al-rafeeq',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'al-rafeeq',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'al-rafeeq',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'email' => 
  array (
    'Email' => 
    array (
      'Email' => 'email',
    ),
  ),
  'facebook' => 
  array (
    'FaceBook' => 
    array (
      'Facebook' => 'facebook',
    ),
  ),
  'finance_permissions' => 
  array (
    'Roles' => 
    array (
      'List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admin' => 
    array (
      'List' => 'sub-admin.index|sub-admin.data',
      'Create' => 'sub-admin.create|sub-admin.store',
      'Edit' => 'sub-admin.edit|sub-admin.update',
      'Status change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'sub-admin.show',
      'Delete' => 'sub-admin.destroy',
    ),
    'System Parameters' => 
    array (
      'List' => 'system-parameters.index|system-parameters.data',
      'Edit' => 'system-parameters.edit|system-parameters.update',
      'View' => 'system-parameters.show',
    ),
    'Joey Reports' => 
    array (
      'Joey Route payout' => 'joey.reports.payout.index|joey.reports.payout.data',
    ),
    'Joey Plans' => 
    array (
      'List' => 'joey-plan.index|joey.plan.data',
      'Create' => 'joey-plan.create|joey-plan.store|get-selected-hub-zones|save-or-update-zone-group',
      'Edit' => 'joey-plan.edit|joey-plan.update|joey.plan.detail.delete|get-selected-hub-zones|save-or-update-zone-group',
      'View' => 'joey-plan.show',
      'Set Plan Base Amount' => 'joey-plan.plan-base-amount.edit|joey-plan.plan-base-amount.update',
      'Set Plan To Joeys' => 'plan-assign-to-joeys.view|plan-assign-to-joyes.update|remove-joey-from-plan',
      'Manage Group By Zone' => 'joey-plan.manage.group-by-zone|get-selected-hub-zones|save-or-update-zone-group',
    ),
    'Assign Plan To Joeys' => 
    array (
      'List' => 'assign-plan-to-joeys.index|assign-plan-to-joeys.data',
      'Assign plan to joey' => 'assign-plan-to-joeys.edit|assign-plan-to-joeys.update',
    ),
    'Merchant Plans' => 
    array (
      'List' => 'merchant-plans.index|merchant-plans.data',
      'Create' => 'merchant-plans.create|merchant-plans.store',
      'Edit' => 'merchant-plans.edit|merchant-plans.update',
      'View' => 'merchant-plans.show',
    ),
    'Set Vendors City' => 
    array (
      'List' => 'set-vendors-city.index|set-vendors-city.data',
      'Create' => 'set-vendors-city.create|set-vendors-city.store',
      'Edit' => 'set-vendors-city.edit|set-vendors-city.update|remove-vendor-from-city',
      'View' => 'set-vendors-city.show',
      'Delete' => 'set-vendors-city.destroy',
    ),
    'Invoices' => 
    array (
      'List' => 'ctc.invoices.index|ctc.invoices.data|ctc.invoice-generate.csv',
      'Edit' => 'ctc.invoices.column.update',
    ),
    'Reporting' => 
    array (
      'CTC Tracking Report' => 'ctc.tracking-report-generate.index|ctc.tracking-report-generate.csv',
      'CTC Narvar Tracking Report' => 'ctc-narvar.tracking-report.index|ctc-narvar.tracking-report-generate.csv',
    ),
    'CTC Brand' => 
    array (
      'List' => 'ctc-brand.index|ctc-brand.data',
      'Add' => 'ctc-brand.create|ctc-brand.store',
      'Edit' => 'ctc-brand.edit|ctc-brand.update|remove-vendor-from-ctc-brand',
      'Delete' => 'ctc-brand.destroy',
    ),
    'Labels & Taxes' => 
    array (
      'List' => 'taxes.index',
      'Edit' => 'taxes.edit|taxes.update',
      'View' => 'taxes.show',
    ),
    'Brokers' => 
    array (
      'List' => 'brokers.index|brooker.data',
      'Add' => 'brokers.create|brokers.store',
      'Edit' => 'brokers.edit|brokers.update',
      'Assign Joeys' => 'brooker-assign-to-joeys.view|brooker-assign-to-joeys|brooker-un-assign-to-joeys',
      'Delete' => 'brokers.destroy',
    ),
    'Flag Order List' => 
    array (
      'List' => 'flag-order.index|flag-order.data',
      'View' => 'flag-order.show',
    ),
    'Economy Fuel rate' => 
    array (
      'List' => 'economy-fuel-rate.index|economy-fuel-rate.data',
      'Add' => 'economy-fuel-rate.create|economy-fuel-rate.store',
    ),
  ),
  'freshcaller_permissions' => 
  array (
    'Fresh Caller Permissions' => 
    array (
      'Make Calls' => 'make_calls',
      'Receieve Calls' => 'receieve_calls',
      'Access Agent Dashboard' => 'access_agent_dashboard',
      'Call Logs' => 'call_logs',
      'Access Contact Information' => 'access_contact_information',
      'Generate Reports' => 'generate_reports',
      'Admin Access' => 'admin_access',
      'Billing' => 'billing',
      'Product Control' => 'product_control',
    ),
  ),
  'freshdesk_permissions' => 
  array (
    'FreshDesk Permissions' => 
    array (
      'View Tickets' => 'view_tickets',
      'Respond Tickets' => 'respond_tickets',
      'Assign Tickets' => 'assign_tickets',
      'Modify Ticket Properties' => 'modify_ticket_properties',
      'Generate Reports' => 'generate_reports',
      'Automatic Ticket Assignment' => 'automatic_ticket_assignment',
      'Edit Configurations' => 'edit_configurations',
      'Billing' => 'billing',
      'Account Management' => 'account_management',
    ),
  ),
  'hr_permissions' => 
  array (
    'User Profile' => 
    array (
      'Edit' => 'users.edit-profile',
      'Change Password' => 'users.change-password',
    ),
    'Roles' => 
    array (
      'Role List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admins' => 
    array (
      'Sub Admin List' => 'sub-admin.index|sub-admin.data',
      'Create' => 'sub-admin.create|sub-admin.store',
      'Edit' => 'sub-admin.edit|sub-admin.update',
      'Status Change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'sub-admin.show',
      'Delete' => 'sub-admin.destroy',
    ),
    'Users' => 
    array (
      'User List' => 'users.index|attendance.users.data',
      'Edit' => 'users.edit|users.update',
      'Status Change' => 'users.active|users.block',
    ),
    'New Requests' => 
    array (
      'New Request List' => 'new-request.index|new-request.data',
      'Edit' => 'unverified-users.edit|unverified-users.update',
      'Delete' => 'new-request.delete',
      'Status Change' => 'new-request.verified|new-request.unverified|ajaxRequestForChecking-WorkingDays.post',
    ),
    'Attendances' => 
    array (
      'Attendance List' => 'attendance.index|attendance.data',
      'Create' => 'attendance.addAttendance|attendance.createAttendance',
      'Edit' => 'attendance.edit|attendance.update',
    ),
    'Attendances Report ' => 
    array (
      'Attendance Report' => 'attendance.report|attendanceReportExcel.data',
      'Attendance Excel' => 'export_reporting.excel',
    ),
    'Pages' => 
    array (
      'Edit' => 'attendancePage.edit|attendancePage.update',
    ),
    'Departments' => 
    array (
      'Department List' => 'department.index|department.data',
      'Create' => 'department.create|department.store',
      'Edit' => 'department.edit|department.update',
      'Status Change' => 'department.active|department.inactive',
    ),
    'Public Holidays' => 
    array (
      'Public Holidays List' => 'public_holidays.index|public_holidays.data',
      'Create' => 'public_holidays.create|public_holidays.insert',
      'Edit' => 'public_holidays.edit|public_holidays.update',
      'Delete' => 'public_holidays.delete',
    ),
    'Hourly Report' => 
    array (
      'Hourly Report' => 'attendance.getHours|attendance.hoursData',
    ),
    'Monthly Report' => 
    array (
      'Monthly Report' => 'attendance.getMonths|attendance.getMonthsUsers',
    ),
    'Leave Types' => 
    array (
      'Leave Types List' => 'leave-type.index|leave_type.data',
      'Create' => 'leave-type.create|leave-type.store',
      'Edit' => 'leave-type.edit|leave-type.update',
      'Delete' => 'leave-type.destory',
    ),
    'Apply For Leaves' => 
    array (
      'Apply For Leaves List' => 'apply-leave.index|apply-leave.data',
      'Create' => 'apply-leave.create|apply-leave.store',
      'Edit' => 'apply-leave.edit|apply-leave.update',
      'Delete' => 'apply-leave.destory',
      'View' => 'apply-leave.show',
    ),
    'Leave' => 
    array (
      'Apply For Leaves List' => 'leave.index|leave.data',
      'View' => 'leave.show',
      'Change Status Of Leave' => 'leave.changeStatus',
    ),
  ),
  'indeed' => 
  array (
    'Indeed' => 
    array (
      'Indeed' => 'indeed',
    ),
  ),
  'linkedin' => 
  array (
    'LinkedIn' => 
    array (
      'LinkedIn' => 'linkedin',
    ),
  ),
  'onboarding_permissions' => 
  array (
    'Roles' => 
    array (
      'Role List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admins' => 
    array (
      'Sub Admin List' => 'sub-admin.index|sub-admin.data',
      'Create' => 'sub-admin.create|sub-admin.store',
      'Edit' => 'sub-admin.edit|sub-admin.update',
      'Status Change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'sub-admin.show',
      'Delete' => 'sub-admin.destroy',
    ),
    'Joeys List' => 
    array (
      'Joeys List' => 'joeys-list.index|joeys.data',
      'Edit' => 'joeys-list.edit|joeys-list.update',
      'Document Not Uploaded' => 'joeys.documentNotUploaded|joeys.documentNotUploadedData|joeys.documentNotUploadedNotification|joeys.bulkDocumentNotUploadedNotification',
      'Document Not Approved' => 'joeys.documentNotApproved|joeys.documentNotApprovedData',
      'Document Approved' => 'joeys.documentApproved|joeys.documentApprovedData',
      'Not Trained' => 'joeys.notTrained|joeys.notTrainedData|joeys.notTrainedNotification|joeys.bulkNotTrainedNotification',
      'Quiz Pending' => 'joeys.quizPending|joeys.quizPendingData|joeys.quizPendingNotification|joeys.bulkQuizPendingNotification',
      'Quiz Passed' => 'joeys.quizPassed|joeys.quizPassedData',
    ),
    'Joey Complaint List' => 
    array (
      'Joey Complaint List' => 'joeys-complaints.index|joeys-complaints.data|joeys-complaints.statusUpdate',
    ),
    'Joey Document Verification' => 
    array (
      'Joey Document Verification List' => 'joey-document-verification.index|joey-document-verification.data',
      'View' => 'joey-document-verification.show',
      'Edit' => 'joey-document-verification.edit|joey-document-verification.update',
      'Joey Expired Document List' => 'joey-document-verification.expiredDocument|joey-expired-document.data',
    ),
    'Joey Attempted Quiz' => 
    array (
      'Joey Attempt Quiz List' => 'joey-attempt-quiz.index|joey-attempt-quiz.data',
      'View' => 'joey-attempt-quiz.show',
    ),
    'Joey Broadcasting Notification' => 
    array (
      'Broadcasting Notification' => 'notification.index|notification.send',
    ),
    'Customer Send Messages' => 
    array (
      'Customer Send Messages List' => 'customer-send-messages.index|customer-send-messages.data',
      'Create' => 'customer-send-messages.create|customer-send-messages.store',
      'Edit' => 'customer-send-messages.edit|customer-send-messages.update',
      'Delete' => 'customer-send-messages.destroy',
    ),
    'Customer Services' => 
    array (
      'Flag List' => 'customer-service.index',
      'Create' => 'customer-service.create|customer-service.store',
      'Edit' => 'customer-service.edit|customer-service.update|customer-services.sub-category.delete',
      'Category Status Change' => 'customer-service.isEnable|customer-service.isDisable',
      'View' => 'customer-service.show',
    ),
    'Categories Order Count' => 
    array (
      'Categories Order Count List' => 'categores.index|categores.data',
      'Create' => 'categores.create|categores.store',
      'Edit' => 'categores.edit|categores.update',
      'Delete' => 'categores.destroy',
    ),
    'Joey Checklists ' => 
    array (
      'Joey Checklists List' => 'joey-checklist.index|joey-checklist.data',
      'Create' => 'joey-checklist.create|joey-checklist.store',
      'Edit' => 'joey-checklist.edit|joey-checklist.update',
      'Delete' => 'joey-checklist.destroy',
    ),
    'Documents' => 
    array (
      'Documents List' => 'documents.index|documents.data',
      'Create' => 'documents.create|documents.store',
      'Edit' => 'documents.edit|documents.update',
      'Delete' => 'documents.destroy',
    ),
    'Zones' => 
    array (
      'Zones List' => 'zones.index|zones.data',
    ),
    'Work Time' => 
    array (
      'Prefered Work Time List' => 'work-time.index|work-time.data',
      'Create' => 'work-time.create|work-time.store',
      'Edit' => 'work-time.edit|work-time.update',
      'Delete' => 'work-time.destroy',
    ),
    'Work Type' => 
    array (
      'Work Type List' => 'work-type.index|work-type.data',
      'Create' => 'work-type.create|work-type.store',
      'Edit' => 'work-type.edit|work-type.update',
      'Delete' => 'work-type.destroy',
    ),
    'Setting' => 
    array (
      'Setting Main Page' => 'dashboard.index',
      'Change Password' => 'users.change-password',
      'Edit Profile' => 'users.edit-profile',
    ),
    'Order Categories' => 
    array (
      'Order Categories List' => 'order-category.index|order-category.data',
      'Create' => 'order-category.create|order-category.store',
      'Edit' => 'order-category.edit|order-category.update',
      'Delete' => 'order-category.destroy',
    ),
    'Training Videos and Documents' => 
    array (
      'Training Videos & Documents List' => 'training.index|training.data',
      'Create' => 'training.create|training.store',
      'Edit' => 'training.edit|training.update',
      'Delete' => 'training.destroy',
    ),
    'Quizes Management' => 
    array (
      'Quizes Management List' => 'quiz-management.index|quiz-management.data',
      'Create' => 'quiz-management.create|quiz-management.store',
      'Edit' => 'quiz-management.edit|quiz-management.update',
      'Delete' => 'quiz-management.destroy',
      'View' => 'quiz-management.show',
    ),
    'FAQs' => 
    array (
      'FAQ List' => 'faqs.index|faqs.data',
      'Create' => 'faqs.create|faqs.store',
      'Edit' => 'faqs.edit|faqs.update',
      'Delete' => 'faqs.destroy',
    ),
  ),
  'park_time' => 
  array (
    'Park Time' => 
    array (
      'Park Time' => 'park_time',
    ),
  ),
  'permissions-bckp' => 
  array (
    'Roles' => 
    array (
      'Roles List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admin' => 
    array (
      'Sub Admins' => 'sub-admin.index|subAdmin.data',
      'Create' => 'subAdmin.add|subAdmin.create',
      'Edit' => 'subAdmin.edit|subAdmin.update',
      'Status change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'subAdmin.profile',
      'Change Password' => 'sub-admin-change.password',
      'Delete' => 'subAdmin.delete',
    ),
    'Montreal Dashboard' => 
    array (
      'Montreal Dashboard' => 'montreal.index|montreal.data',
      'Montreal View' => 'montreal.profile',
      'Montreal Excel' => 'export_Montreal.excel',
      'Sorted Order' => 'montreal-sort.index|montrealSorted.data',
      'Montreal Sorted View' => 'montreal_sorted.profile',
      'Sorted Excel' => 'export_MontrealSorted.excel',
      'Pickup From Hub' => 'montreal-pickup.index|montrealPickedUp.data',
      'Montreal Pickup View' => 'montreal_pickup.profile',
      'Pick Up Excel' => 'export_MontrealPickedUp.excel',
      'Not Scan' => 'montreal-not-scan.index|montrealNotScan.data',
      'Montreal Not Scan View' => 'montreal_notscan.profile',
      'Not Scan Excel' => 'export_MontrealNotScan.excel',
      'Delivered Orders' => 'montreal-delivered.index|montrealDelivered.data',
      'Montreal Delivered View' => 'montreal_delivered.profile',
      'Delivered Excel' => 'export_MontrealDelivered.excel',
      'Route Information' => 'montreal-route-info.index',
      'Route Detail' => 'montreal_route.detail',
      'Route Order Detail' => 'montrealinfo_route.detail',
      'Route Info Excel' => 'export_MontrealRouteInfo.excel',
    ),
    'Ottawa Dashboard' => 
    array (
      'Ottawa Dashboard' => 'ottawa.index|ottawa.data',
      'Ottawa View' => 'ottawa.profile',
      'Ottawa Excel' => 'export_Ottawa.excel',
      'Sorted Order' => 'ottawa-sort.index|ottawaSorted.data',
      'Ottawa Sorted View' => 'ottawa_sorted.profile',
      'Sorted Excel' => 'export_OttawaSorted.excel',
      'Pickup From Hub' => 'ottawa-pickup.index|ottawaPickedUp.data',
      'Ottawa Pickup View' => 'ottawa_pickup.profile',
      'Pick Up Excel' => 'export_OttawaPickedUp.excel',
      'Not Scan' => 'ottawa-not-scan.index|ottawaNotScan.data',
      'Ottawa Not Scan View' => 'ottawa_notscan.profile',
      'Not Scan Excel' => 'export_OttawaNotScan.excel',
      'Delivered Orders' => 'ottawa-delivered.index|ottawaDelivered.data',
      'Ottawa Delivered View' => 'ottawa_delivered.profile',
      'Delivered Excel' => 'export_OttawaDelivered.excel',
      'Route Information' => 'ottawa-route-info.index',
      'Route Detail' => 'ottawa_route.detail',
      'Route Order Detail' => 'ottawainfo_route.detail',
      'Route Info Excel' => 'export_OttawaRouteInfo.excel',
    ),
    'CTC Dashboard' => 
    array (
      'CTC Dashboard' => 'ctc.index|ctc.data',
      'CTC View' => 'ctc.profile',
      'CTC Excel' => 'export_ctc.excel',
      'Sorted Order' => 'ctc-sort.index|ctcSorted.data',
      'CTC Sorted View' => 'ctc_sorted.profile',
      'Sorted Excel' => 'export_CTCSorted.excel',
      'Pickup From Hub' => 'ctc-pickup.index|ctcPickedUp.data',
      'CTC Pickup View' => 'ctc_pickup.profile',
      'Pick Up Excel' => 'export_CTCPickedUp.excel',
      'Not Scan' => 'ctc-not-scan.index|ctcNotScan.data',
      'CTC Not Scan View' => 'ctc_notscan.profile',
      'Not Scan Excel' => 'export_CTCNotScan.excel',
      'Delivered Orders' => 'ctc-delivered.index|ctcDelivered.data',
      'CTC Delivered View' => 'ctc_delivered.profile',
      'Delivered Excel' => 'export_CTCDelivered.excel',
      'Route Information' => 'ctc-route-info.index',
      'Route Detail' => 'ctc_route.detail',
      'Route Order Detail' => 'ctcinfo_route.detail',
      'Route Info Excel' => 'export_CTCRouteInfo.excel',
    ),
    'Walmart Dashboard' => 
    array (
      'Walmart Dashboard' => 'walmartdashboard.index|walmartotdajax.index|walmartshortsummary.index|walmartrenderorder.index|walmartontimeorder.index|walmartstoresdata.index|walmartordersummary.index',
      'Walmart Dashboard Excel' => 'walmartdashboard.excel',
      'Walmart Dashboard Reporting' => 'download-walmart-report-csv-view|generate-walmart-report-csv',
    ),
    'Loblaws Dashboard' => 
    array (
      'Loblaws Dashboard' => 'loblawsdashboard.index|loblawsajaxorder.index|loblawsotdcharts.index|loblawsajaxotacharts.index|loblawstotalorder.index',
      'Loblaws Dashboard Reporting' => 'loblaws-dashboard-reporting-csv|generate-loblaws-report-csv',
      'Loblaws Calgary' => 'loblawscalgary.index|loblawscalgary_orders.index|loblawscalgary_otd_charts.index|loblawscalgary_ota_charts.index|loblawscalgary_total_order.index',
      'Loblaws Home Deliveryb' => 'loblawshome.index|loblawshome_order.index|loblawshome_otd_charts.index|loblawshome_ota_charts.index|loblawshome_total_order.index',
      'Loblaws Home Delivery Reporting' => 'loblaws-homedelivery-dashboard-reporting-csv|generate-loblaws-homedelivery-report-csv',
    ),
    'Other Action' => 
    array (
      'Update Status' => 'hub-routific.index|hub-routific-update.Update',
      'Update Multiple Status' => 'multiple-tracking-id.index|multiple-tracking-id.update',
      'Search Order' => 'searchorder.index',
      'Search Order Update' => 'update-order.update',
      'Search By Multiple Order' => 'search-multiple-tracking.index',
      'Order Detail' => 'searchorder.show',
      'Delete Route' => 'route.index|route.destroy',
    ),
    'Vendor Reporting' => 
    array (
      'Vendor Reporting' => 'reporting.index|reporting.data',
      'Vendor Reporting Excel' => 'export_reporting.excel',
    ),
    'CTC Reporting' => 
    array (
      'CTC Reporting' => 'ctc_reporting.index',
    ),
  ),
  'permissions' => 
  array (
    'Management Portal' => 
    array (
      'Management View' => 'statistics.index|statistics-day-otd.index|statistics-week-otd.index|statistics-month-otd.index|statistics-year-otd.index|statistics-all-counts.index|statistics-failed-counts.index|statistics-custom-counts.index|statistics-manual-counts.index|statistics-route-counts.index|statistics-route-detail.index|statistics-on-time-counts.index|statistics-top-ten-joeys.index|statistics-least-ten-joeys.index|statistics-graph.index|statistics-brooker.index|statistics-order.index|statistics-failed-order.index|statistics-brooker-detail.index|statistics-brooker-detail-day-otd.index|statistics-brooker-detail-week-otd.index|statistics-brooker-detail-month-otd.index|statistics-brooker-detail-year-otd.index|statistics-brooker-detail-all-counts.index|statistics-brooker-detail-failed-counts.index|statistics-brooker-detail-custom-counts.index|statistics-brooker-detail-manual-counts.index|statistics-brooker-detail-route-counts.index|statistics-brooker-detail-on-time-counts.index|statistics-brooker-detail-top-ten-joeys.index|statistics-brooker-detail-least-ten-joeys.index|statistics-brooker-detail-graph.index|statistics-brooker-detail-brooker.index|statistics-brooker-detail-order.index|statistics-brooker-detail-failed-order.index|statistics-brooker-detail-all-joeys-otd.index|statistics-brooker-detail-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index|statistics-flag-order-list-pie-chart-data',
      'Joey Management View' => 'joey-management.index|joey-management-joey-count.index|joey-management-joey-count.onduty|joey-management-orders-count.index|joey-management-otd-day.index|joey-management-otd-week.index|joey-management-otd-month.index|joey-management-list.index|joey-management-order-list.index|joey-management-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index',
      'Brooker Management View' => 'brooker-management.index|brooker-management-brooker-count.index|brooker-management-joey-count.index|brooker-management-joey-count.onduty|brooker-management-orders-count.index|brooker-management-otd-day.index|brooker-management-otd-week.index|brooker-management-otd-month.index|brooker-management-list.index|brooker-management-brooker-list.index|brooker-management-all-brooker-otd.index|joey-management-all-brooker-otd.index|statistics-brooker-detail.index|statistics-brooker-detail-day-otd.index|statistics-brooker-detail-week-otd.index|statistics-brooker-detail-month-otd.index|statistics-brooker-detail-year-otd.index|statistics-brooker-detail-all-counts.index|statistics-brooker-detail-failed-counts.index|statistics-brooker-detail-custom-counts.index|statistics-brooker-detail-manual-counts.index|statistics-brooker-detail-route-counts.index|statistics-brooker-detail-on-time-counts.index|statistics-brooker-detail-top-ten-joeys.index|statistics-brooker-detail-least-ten-joeys.index|statistics-brooker-detail-graph.index|statistics-brooker-detail-brooker.index|statistics-brooker-detail-order.index|statistics-brooker-detail-failed-order.index|statistics-brooker-detail-all-joeys-otd.index|statistics-brooker-detail-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index',
      'In Bound' => 'statistics-inbound.index|statistics-inbound-data.index|statistics-setup-time.index|statistics-sorting-time.index|statistics-inbound.wareHouseSorterUpdate',
      'Out Bound' => 'statistics-outbound.index|statistics-outbound-data.index|statistics-dispensing-time.index|statistics-outbound.wareHouseSorterUpdate',
      'Summary' => 'warehouse-summary.index|warehouse-summary-data.index',
      'Manager' => 'manager.index|manager.create|manager.store|manager.edit|manager.update|manager.show|check-for-hub',
      'Alert System' => 'alert-system.index|warehousesorter.index|warehousesorter.data|warehousesorter.add|warehousesorter.create|warehousesorter.profile|warehousesorter.edit|warehousesorter.update|warehousesorter.delete',
    ),
    'Roles' => 
    array (
      'Roles List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admin' => 
    array (
      'Sub Admins' => 'sub-admin.index|subAdmin.data',
      'Create' => 'subAdmin.add|subAdmin.create',
      'Edit' => 'subAdmin.edit|subAdmin.update',
      'Status change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'subAdmin.profile',
      'Change Password' => 'sub-admin-change.password|sub-admin-create.password',
      'Account Security' => 'account-security.edit|account-security.update',
      'Delete' => 'subAdmin.delete',
    ),
    'Ctc Sub Admin' => 
    array (
      'Sub Admins' => 'ctc-subadmin.index|ctc-subadmin.data',
      'Create' => 'ctc-subadmin.add|ctc-subadmin.create',
      'Edit' => 'ctc-subadmin.edit|ctc-subadmin.update',
      'Status change' => 'ctc-subadmin.active|ctc-subadmin.inactive',
      'View' => 'ctc-subadmin.profile',
      'Delete' => 'ctc-subadmin.delete',
    ),
    'New Montreal Dashboard' => 
    array (
      'New Montreal Dashboard' => 'newmontreal.index|newmontreal.data|newmontreal.totalcards|newmontreal.mainfestcards|newmontreal.failedcards|newmontreal.customroutecards|newmontreal.yesterdaycards|newmontreal.route-list|newmontreal.joey-list|newmontreal-dashboard.index',
      'Montreal View' => 'newmontreal.profile',
      'Montreal Excel' => 'newexport_Montreal.excel',
      'New Sorted Order' => 'newmontreal-sort.index|newmontrealSorted.data',
      'Montreal Sorted View' => 'newmontreal_sorted.profile',
      'Sorted Excel' => 'newexport_MontrealSorted.excel',
      'New Pickup From Hub' => 'newmontreal-pickup.index|newmontrealPickedUp.data',
      'Montreal Pickup View' => 'newmontreal_pickup.profile',
      'Pick Up Excel' => 'newexport_MontrealPickedUp.excel',
      'New Not Scan' => 'newmontreal-not-scan.index|newmontrealNotScan.data',
      'Montreal Not Scan View' => 'newmontreal_notscan.profile',
      'Not Scan Excel' => 'newexport_MontrealNotScan.excel',
      'New Delivered Orders' => 'newmontreal-delivered.index|newmontrealDelivered.data',
      'Montreal Delivered View' => 'newmontreal_delivered.profile',
      'Delivered Excel' => 'newexport_MontrealDelivered.excel',
      'New Returned Orders' => 'newmontreal-returned.index|newmontrealReturned.data|newmontreal-notreturned.index|newmontrealNotReturned.data',
      'Montreal Returned View' => 'newmontreal_returned.profile|newmontreal_notreturned.profile',
      'Returned Excel' => 'newexport_MontrealReturned.excel|newexport_MontrealNotReturned.excel|newexport_MontrealNotReturned_Tracking.excel',
      'New Custom Route Orders' => 'newmontreal-custom-route.index|newmontrealCustomRoute.data',
      'Montreal Custom Route View' => 'newmontreal_customroute.profile',
      'Custom Route Excel' => 'newexport_MontrealCustomRoute.excel',
      'Route Information' => 'newmontreal-route-info.index|newmontreal_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Detail' => 'newmontreal_route.detail|newmontreal_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Order Detail' => 'newmontrealinfo_route.detail',
      'Route Info Excel' => 'newexport_MontrealRouteInfo.excel',
      'Notes' => 'newmontreal-route-info.addNote|newmontreal-route-info.getNotes',
    ),
    'New Ottawa Dashboard' => 
    array (
      'New Ottawa Dashboard' => 'newottawa.index|newottawa.data|newottawa.totalcards|newottawa.mainfestcards|newottawa.failedcards|newottawa.customroutecards|newottawa.yesterdaycards|newottawa.ottawa-route-list|newottawa.ottawa-joey-list|newottawa-dashboard.index',
      'Ottawa View' => 'newottawa.profile',
      'Ottawa Excel' => 'newexport_Ottawa.excel',
      'New Sorted Order' => 'newottawa-sort.index|newottawaSorted.data',
      'Ottawa Sorted View' => 'newottawa_sorted.profile',
      'Sorted Excel' => 'newexport_OttawaSorted.excel',
      'New Pickup From Hub' => 'newottawa-pickup.index|newottawaPickedUp.data',
      'Ottawa Pickup View' => 'newottawa_pickup.profile',
      'Pick Up Excel' => 'newexport_OttawaPickedUp.excel',
      'New Not Scan' => 'newottawa-not-scan.index|newottawaNotScan.data',
      'Ottawa Not Scan View' => 'newottawa_notscan.profile',
      'Not Scan Excel' => 'newexport_OttawaNotScan.excel',
      'New Delivered Orders' => 'newottawa-delivered.index|newottawaDelivered.data',
      'Ottawa Delivered View' => 'newottawa_delivered.profile',
      'Delivered Excel' => 'newexport_OttawaDelivered.excel',
      'New Returned Orders' => 'newottawa-returned.index|newottawaReturned.data|newottawa-notreturned.index|newottawaNotReturned.data',
      'Returned Excel' => 'newexport_OttawaReturned.excel|newexport_OttawaNotReturned.excel|newexport_OttawaNotReturned_tracking.excel',
      'Ottawa Returned View' => 'newottawa_returned.profile|newottawa_notreturned.profile',
      'New Custom Route Orders' => 'newottawa-custom-route.index|newottawaCustomRoute.data',
      'Custom Route Excel' => 'newexport_OttawaCustomRoute.excel',
      'Ottawa Custom Route View' => 'newottawa_CustomRoute.profile',
      'Route Information' => 'newottawa-route-info.index|newottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Detail' => 'newottawa_route.detail|newottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Order Detail' => 'newottawainfo_route.detail',
      'Route Info Excel' => 'newexport_OttawaRouteInfo.excel',
      'Notes' => 'newottawa-route-info.addNote|newottawa-route-info.getNotes',
    ),
    'CTC Dashboard' => 
    array (
      'CTC Dashboard' => 'ctc-dashboard.index|ctc-dashboard.data',
      'CTC View' => 'ctc-new.profile',
      'CTC Excel' => 'export_ctc_new_dashboard.excel',
      'OTD Report' => 'export_ctc_new_dashboard_otd_report.excel',
      'CTC Summary' => 'ctc_reporting.index|ctc_reporting_data.data',
      'CTC Summary View' => 'ctc-summary.profile',
      'CTC Graph' => 'ctc-graph.index|ctc-otd-day.index|ctc-otd-week.index|ctc-otd-month.index',
      'Route Information' => 'ctc-route-info.index|ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Detail' => 'ctc_route.detail|ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Mark Delay' => 'route-mark-delay',
      'Route Order Detail' => 'ctcinfo_route.detail',
      'Route Info Excel' => 'export_CTCRouteInfo.excel',
      'New CTC Dashboard' => 'new-order-ctc.data|new-order-ctc.index|new-ctc-card-dashboard.index|new-ctc.totalcards|new-ctc.customroutecards|new-ctc.yesterdaycards',
      'New CTC View' => 'new-ctc-detail-detail.profile',
      'New CTC Excel' => 'new-order-ctc-export.excel',
      'Sorted Order' => 'new-sort-ctc.index|new-sort-ctc.data',
      'CTC Sorted View' => 'new-ctc-sorted-detail.profile',
      'Sorted Excel' => 'new-sort-ctc-export.excel',
      'Pickup From Hub' => 'new-pickup-ctc.index|new-pickup-ctc.data',
      'CTC Pickup View' => 'new-ctc-pickup-detail.profile',
      'Pick Up Excel' => 'new-pickup-ctc-export.excel',
      'Not Scan' => 'new-not-scan-ctc.index|new-not-scan-ctc.data',
      'CTC Not Scan View' => 'new-ctc-notscan-detail.profile',
      'Not Scan Excel' => 'new-not-scan-ctc-export.excel',
      'Delivered Orders' => 'new-delivered-ctc.index|new-delivered-ctc.data',
      'CTC Delivered View' => 'new-ctc-delivered-detail.profile',
      'Delivered Excel' => 'new-delivered-ctc-export.excel',
      'Returned Orders' => 'new-returned-ctc.index|new-returned-ctc.data|new-notreturned-ctc.index|new-notreturned-ctc.data',
      'Returned Excel' => 'new-returned-ctc-export.excel|new-notreturned-ctc-export.excel|new-notreturned-ctc-tracking-export.excel',
      'CTC Returned View' => 'new-ctc-returned-detail.profile|new-ctc-notreturned-detail.profile',
      'Custom Route Orders' => 'new-custom-route-ctc.index|new-custom-route-ctc.data',
      'Custom Route Excel' => 'new-custom-route-ctc-export.excel',
      'CTC Custom Route View' => 'new-ctc-CustomRoute-detail.profile',
      'Notes' => 'new-ctc-route-info.addNote|new-ctc-route-info.getNotes',
    ),
    'Borderless Dashboard' => 
    array (
      'Borderless Dashboard' => 'borderless-dashboard.index|borderless-dashboard.data',
      'Borderless View' => 'borderless-order.profile',
      'Borderless Excel' => 'borderless-dashboard-export.excel',
      'OTD Report' => 'borderless-dashboard-export-otd-report.excel',
      'Borderless Summary' => 'borderless_reporting.index|new_borderless_reporting_data.data',
      'Borderless Summary View' => 'borderless-summary.profile',
      'Borderless Graph' => 'borderless-graph.index|borderless-otd-day.index|borderless-otd-week.index|borderless-otd-month.index',
      'Route Information' => 'borderless-route-info.index|borderlessinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Route Detail' => 'borderless_route.detail|borderlessinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
      'Mark Delay' => 'borderless-route-mark-delay',
      'Route Order Detail' => 'borderlessinfo_route.detail',
      'Route Info Excel' => 'export_BorderlessRouteInfo.excel',
      'New Borderless Dashboard' => 'new-order-borderless.data|new-order-borderless.index|new-borderless-card-dashboard.index|new-borderless.totalcards|new-borderless.customroutecards|new-borderless.yesterdaycards',
      'New Borderless View' => 'new-borderless-detail-detail.profile',
      'New Borderless Excel' => 'new-order-borderless-export.excel',
      'Sorted Order' => 'new-sort-borderless.index|new-sort-borderless.data',
      'Borderless Sorted View' => 'new-borderless-sorted-detail.profile',
      'Sorted Excel' => 'new-sort-borderless-export.excel',
      'Pickup From Hub' => 'new-pickup-borderless.index|new-pickup-borderless.data',
      'Borderless Pickup View' => 'new-borderless-pickup-detail.profile',
      'Pick Up Excel' => 'new-pickup-borderless-export.excel',
      'Not Scan' => 'new-not-scan-borderless.index|new-not-scan-borderless.data',
      'Borderless Not Scan View' => 'new-borderless-notscan-detail.profile',
      'Not Scan Excel' => 'new-not-scan-borderless-export.excel',
      'Delivered Orders' => 'new-delivered-borderless.index|new-delivered-borderless.data',
      'Borderless Delivered View' => 'new-borderless-delivered-detail.profile',
      'Delivered Excel' => 'new-delivered-borderless-export.excel',
      'Returned Orders' => 'new-returned-borderless.index|new-returned-borderless.data|new-notreturned-borderless.index|new-notreturned-borderless.data',
      'Returned Excel' => 'new-returned-borderless-export.excel|new-notreturned-borderless-export.excel|new-notreturned-borderless-tracking-export.excel',
      'Borderless Returned View' => 'new-borderless-returned-detail.profile|new-borderless-notreturned-detail.profile',
      'Custom Route Orders' => 'new-custom-route-borderless.index|new-custom-route-borderless.data',
      'Custom Route Excel' => 'new-custom-route-borderless-export.excel',
      'Borderless Custom Route View' => 'new-borderless-CustomRoute-detail.profile',
      'Notes' => 'new-borderless-route-info.addNote|new-borderless-route-info.getNotes',
    ),
    'Return Dashboard' => 
    array (
      'Return Route Information' => 'return-route-info.index|return-route-order.detail|return-route-info-order.detail',
    ),
    'Toronto Flower Dashboard' => 
    array (
      'Route Information' => 'toronto-flower-route-info.index',
      'Route Detail' => 'toronto_flower_route.detail',
      'Route Order Detail' => 'toronto_flower_info_route.detail',
      'Route Info Excel' => 'export_toronto_flower_route_info.excel',
    ),
    'Walmart Dashboard' => 
    array (
      'Walmart Dashboard' => 'walmartdashboard.index|walmartotdajax.index|walmartshortsummary.index|walmartrenderorder.index|walmartontimeorder.index|walmartstoresdata.index|walmartordersummary.index',
      'Walmart Dashboard Excel' => 'walmartdashboard.excel',
      'Walmart Dashboard Reporting' => 'download-walmart-report-csv-view|generate-walmart-report-csv',
    ),
    'Loblaws Dashboard' => 
    array (
      'Loblaws Dashboard' => 'loblawsdashboard.index|loblawsajaxorder.index|loblawsotdcharts.index|loblawsajaxotacharts.index|loblawstotalorder.index',
      'Loblaws Calgary' => 'loblawscalgary.index|loblawscalgary_orders.index|loblawscalgary_otd_charts.index|loblawscalgary_ota_charts.index|loblawscalgary_total_order.index',
      'Loblaws Home Delivery' => 'loblawshome.index|loblawshome_order.index|loblawshome_otd_charts.index|loblawshome_ota_charts.index|loblawshome_total_order.index',
      'Loblaws Re-Processing' => 'loblaws.order-reprocessing|loblaws.order-reprocessing-update',
      'Loblaws Home Delivery Reporting' => 'loblaws-homedelivery-dashboard-reporting-csv|generate-loblaws-homedelivery-report-csv',
      'Loblaws Dashboard Reporting' => 'loblaws-dashboard-reporting-csv|generate-loblaws-report-csv',
      'Loblaws Calgary Reporting' => 'loblaws-calgary-dashboard-reporting-csv|generate-calgary-loblaws-report-csv',
    ),
    'Good Food Dashboard' => 
    array (
      'Good Food Dashboard' => 'goodfood.index|goodfood_order.index|goodfood_otd_charts.index|goodfood_ota_charts.index|goodfood-new-count',
      'Good Food Reporting' => 'goodfood-dashboard-reporting-csv|generate-goodfood-report-csv',
    ),
    'Grocery Dashboard' => 
    array (
      'Grocery Dashboard' => 'grocerydashboard.index|groceryajaxorder.index|groceryotdcharts.index',
    ),
    'Other Action' => 
    array (
      'Update Orders' => 'multiple-tracking-id.index|multiple-tracking-id.update',
      'Search Order' => 'search-multiple-tracking.index|searchorder.show|update-order.update',
      'Flag / Un-flag Orders' => 'flag.create|un-flag',
      'Upload Image' => 'sprint-image-upload',
      'Manual Status History' => 'manual-status.index|manual-status.data',
      'Generate Csv' => 'generate-csv',
      'Manual Tracking Report' => 'manual-tracking-report.index|manual-tracking.data',
      'Manual Tracking Report Excel' => 'manual-tracking.excel|download-file-tracking',
      'Tracking' => 'search-tracking.index|searchtrackingdetails.show',
    ),
    'DNR Reporting' => 
    array (
      'DNR Reporting' => 'dnr.index|dnr.data',
      'DNR Excel' => 'dnr.export',
    ),
    'Customer Support' => 
    array (
      'Customer Support' => 'order-confirmation-list.index|orderConfirmation.transfer|Column.Update|add-notes|show-notes',
      'History' => 'order-confirmation.history|show-notes',
      'Return To Merchant' => 'expired-order.history|return-order.update|show-notes',
      'Returned Order' => 'returned-order.index|show-notes',
    ),
    'Flag Order Details' => 
    array (
      'Flag Order List' => 'flag-order-list.index|flag-order-list.data|flag-order-list-pie-chart-data',
      'Flag Order Detail' => 'flag-order.details',
      'Approved Flag List' => 'approved-flag-list.index|approved-flag-list.data|flag-order.details',
      'Un-Approved Flag List' => 'un-approved-flag-list.index|un-approved-flag-list.data',
      'Mark Approved' => 'joey-performance-status.update',
      'Blocked Joey List' => 'block-joey-flag-list.index|block-joey-flag-list.data',
      'Unblock Joey' => 'unblock-joey-flag.update',
    ),
    'Reason' => 
    array (
      'Reason' => 'reason.index|reason.data',
      'Create' => 'reason.add|reason.create',
      'Edit' => 'reason.edit|reason.update',
      'Delete' => 'reason.delete',
    ),
    'Rights' => 
    array (
      'Right List' => 'right.index',
      'Create' => 'right.create|right.store',
      'Edit' => 'right.edit|right.update',
      'View' => 'right.show',
    ),
  ),
  'permissions1' => 
  array (
    'Roles' => 
    array (
      'Roles List' => 'role.index',
      'Create' => 'role.create|role.store',
      'Edit' => 'role.edit|role.update',
      'View' => 'role.show',
      'Set permissions' => 'role.set-permissions|role.set-permissions.update',
    ),
    'Sub Admin' => 
    array (
      'Sub Admins' => 'sub-admin.index|subAdmin.data',
      'Create' => 'subAdmin.add|subAdmin.create',
      'Edit' => 'subAdmin.edit|subAdmin.update',
      'Status change' => 'sub-admin.active|sub-admin.inactive',
      'View' => 'subAdmin.profile',
      'Change Password' => 'sub-admin-change.password|sub-admin-create.password',
      'Account Security' => 'account-security.edit|account-security.update',
      'Delete' => 'subAdmin.delete',
    ),
    'Ctc Sub Admin' => 
    array (
      'Sub Admins' => 'ctc-subadmin.index|ctc-subadmin.data',
      'Create' => 'ctc-subadmin.add|ctc-subadmin.create',
      'Edit' => 'ctc-subadmin.edit|ctc-subadmin.update',
      'Status change' => 'ctc-subadmin.active|ctc-subadmin.inactive',
      'View' => 'ctc-subadmin.profile',
      'Delete' => 'ctc-subadmin.delete',
    ),
    'New Montreal Dashboard' => 
    array (
      'New Montreal Dashboard' => 'newmontreal.index|newmontreal.data',
      'Montreal View' => 'newmontreal.profile',
      'Montreal Excel' => 'newexport_Montreal.excel',
      'New Sorted Order' => 'newmontreal-sort.index|newmontrealSorted.data',
      'Montreal Sorted View' => 'newmontreal_sorted.profile',
      'Sorted Excel' => 'newexport_MontrealSorted.excel',
      'New Pickup From Hub' => 'newmontreal-pickup.index|newmontrealPickedUp.data',
      'Montreal Pickup View' => 'newmontreal_pickup.profile',
      'Pick Up Excel' => 'newexport_MontrealPickedUp.excel',
      'New Not Scan' => 'newmontreal-not-scan.index|newmontrealNotScan.data',
      'Montreal Not Scan View' => 'newmontreal_notscan.profile',
      'Not Scan Excel' => 'newexport_MontrealNotScan.excel',
      'New Delivered Orders' => 'newmontreal-delivered.index|newmontrealDelivered.data',
      'Montreal Delivered View' => 'newmontreal_delivered.profile',
      'Delivered Excel' => 'newexport_MontrealDelivered.excel',
      'New Returned Orders' => 'newmontreal-returned.index|newmontrealReturned.data',
      'Montreal Returned View' => 'newmontreal_returned.profile',
      'Returned Excel' => 'newexport_MontrealReturned.excel',
      'New Custom Route Orders' => 'newmontreal-custom-route.index|newmontrealCustomRoute.data',
      'Montreal Custom Route View' => 'newmontreal_customroute.profile',
      'Custom Route Excel' => 'newexport_MontrealCustomRoute.excel',
    ),
    'Montreal Dashboard' => 
    array (
      'Montreal Dashboard' => 'montreal.index|montreal.data',
      'Montreal View' => 'montreal.profile',
      'Montreal Excel' => 'export_Montreal.excel',
      'Sorted Order' => 'montreal-sort.index|montrealSorted.data',
      'Montreal Sorted View' => 'montreal_sorted.profile',
      'Sorted Excel' => 'export_MontrealSorted.excel',
      'Pickup From Hub' => 'montreal-pickup.index|montrealPickedUp.data',
      'Montreal Pickup View' => 'montreal_pickup.profile',
      'Pick Up Excel' => 'export_MontrealPickedUp.excel',
      'Not Scan' => 'montreal-not-scan.index|montrealNotScan.data',
      'Montreal Not Scan View' => 'montreal_notscan.profile',
      'Not Scan Excel' => 'export_MontrealNotScan.excel',
      'Delivered Orders' => 'montreal-delivered.index|montrealDelivered.data',
      'Montreal Delivered View' => 'montreal_delivered.profile',
      'Delivered Excel' => 'export_MontrealDelivered.excel',
      'Returned Orders' => 'montreal-returned.index|montrealReturned.data',
      'Montreal Returned View' => 'montreal_returned.profile',
      'Returned Excel' => 'export_MontrealReturned.excel',
      'Route Information' => 'montreal-route-info.index',
      'Route Detail' => 'montreal_route.detail',
      'Route Order Detail' => 'montrealinfo_route.detail',
      'Route Info Excel' => 'export_MontrealRouteInfo.excel',
    ),
    'New Ottawa Dashboard' => 
    array (
      'New Ottawa Dashboard' => 'newottawa.index|newottawa.data',
      'Ottawa View' => 'newottawa.profile',
      'Ottawa Excel' => 'newexport_Ottawa.excel',
      'New Sorted Order' => 'newottawa-sort.index|newottawaSorted.data',
      'Ottawa Sorted View' => 'newottawa_sorted.profile',
      'Sorted Excel' => 'newexport_OttawaSorted.excel',
      'New Pickup From Hub' => 'newottawa-pickup.index|newottawaPickedUp.data',
      'Ottawa Pickup View' => 'newottawa_pickup.profile',
      'Pick Up Excel' => 'newexport_OttawaPickedUp.excel',
      'New Not Scan' => 'newottawa-not-scan.index|newottawaNotScan.data',
      'Ottawa Not Scan View' => 'newottawa_notscan.profile',
      'Not Scan Excel' => 'newexport_OttawaNotScan.excel',
      'New Delivered Orders' => 'newottawa-delivered.index|newottawaDelivered.data',
      'Ottawa Delivered View' => 'newottawa_delivered.profile',
      'Delivered Excel' => 'newexport_OttawaDelivered.excel',
      'New Returned Orders' => 'newottawa-returned.index|newottawaReturned.data',
      'Returned Excel' => 'newexport_OttawaReturned.excel',
      'Ottawa Returned View' => 'newottawa_returned.profile',
      'New Custom Route Orders' => 'newottawa-custom-route.index|newottawaCustomRoute.data',
      'Custom Route Excel' => 'newexport_OttawaCustomRoute.excel',
      'Ottawa Custom Route View' => 'newottawa_CustomRoute.profile',
    ),
    'Ottawa Dashboard' => 
    array (
      'Ottawa Dashboard' => 'ottawa.index|ottawa.data',
      'Ottawa View' => 'ottawa.profile',
      'Ottawa Excel' => 'export_Ottawa.excel',
      'Sorted Order' => 'ottawa-sort.index|ottawaSorted.data',
      'Ottawa Sorted View' => 'ottawa_sorted.profile',
      'Sorted Excel' => 'export_OttawaSorted.excel',
      'Pickup From Hub' => 'ottawa-pickup.index|ottawaPickedUp.data',
      'Ottawa Pickup View' => 'ottawa_pickup.profile',
      'Pick Up Excel' => 'export_OttawaPickedUp.excel',
      'Not Scan' => 'ottawa-not-scan.index|ottawaNotScan.data',
      'Ottawa Not Scan View' => 'ottawa_notscan.profile',
      'Not Scan Excel' => 'export_OttawaNotScan.excel',
      'Delivered Orders' => 'ottawa-delivered.index|ottawaDelivered.data',
      'Ottawa Delivered View' => 'ottawa_delivered.profile',
      'Delivered Excel' => 'export_OttawaDelivered.excel',
      'Returned Excel' => 'export_OttawaReturned.excel',
      'Ottawa Returned View' => 'ottawa_returned.profile',
      'Returned Orders' => 'ottawa-returned.index|ottawaReturned.data',
      'Route Information' => 'ottawa-route-info.index',
      'Route Detail' => 'ottawa_route.detail',
      'Route Order Detail' => 'ottawainfo_route.detail',
      'Route Info Excel' => 'export_OttawaRouteInfo.excel',
    ),
    'CTC Dashboard' => 
    array (
      'CTC Dashboard' => 'ctc-dashboard.index|ctc-dashboard.data',
      'CTC View' => 'ctc-new.profile',
      'CTC Excel' => 'export_ctc_new_dashboard.excel',
      'CTC Summary' => 'ctc_reporting.index|ctc_reporting_data.data',
      'CTC Summary View' => 'ctc-summary.profile',
      'CTC Graph' => 'ctc-graph.index|ctc-otd-day.index|ctc-otd-week.index|ctc-otd-month.index',
      'Route Information' => 'ctc-route-info.index',
      'Route Detail' => 'ctc_route.detail',
      'Mark Delay' => 'route-mark-delay',
      'Route Order Detail' => 'ctcinfo_route.detail',
      'Route Info Excel' => 'export_CTCRouteInfo.excel',
    ),
    'Toronto Flower Dashboard' => 
    array (
      'Route Information' => 'toronto-flower-route-info.index',
      'Route Detail' => 'toronto_flower_route.detail',
      'Route Order Detail' => 'toronto_flower_info_route.detail',
      'Route Info Excel' => 'export_toronto_flower_route_info.excel',
    ),
    'Walmart Dashboard' => 
    array (
      'Walmart Dashboard' => 'walmartdashboard.index|walmartotdajax.index|walmartshortsummary.index|walmartrenderorder.index|walmartontimeorder.index|walmartstoresdata.index|walmartordersummary.index',
      'Walmart Dashboard Excel' => 'walmartdashboard.excel',
      'Walmart Dashboard Reporting' => 'download-walmart-report-csv-view|generate-walmart-report-csv',
    ),
    'Loblaws Dashboard' => 
    array (
      'Loblaws Dashboard' => 'loblawsdashboard.index|loblawsajaxorder.index|loblawsotdcharts.index|loblawsajaxotacharts.index|loblawstotalorder.index',
      'Loblaws Calgary' => 'loblawscalgary.index|loblawscalgary_orders.index|loblawscalgary_otd_charts.index|loblawscalgary_ota_charts.index|loblawscalgary_total_order.index',
      'Loblaws Home Delivery' => 'loblawshome.index|loblawshome_order.index|loblawshome_otd_charts.index|loblawshome_ota_charts.index|loblawshome_total_order.index',
      'Loblaws Re-Processing' => 'loblaws.order-reprocessing|loblaws.order-reprocessing-update',
      'Loblaws Home Delivery Reporting' => 'loblaws-homedelivery-dashboard-reporting-csv|generate-loblaws-homedelivery-report-csv',
      'Loblaws Dashboard Reporting' => 'loblaws-dashboard-reporting-csv|generate-loblaws-report-csv',
      'Loblaws Calgary Reporting' => 'loblaws-calgary-dashboard-reporting-csv|generate-calgary-loblaws-report-csv',
    ),
    'Grocery Dashboard' => 
    array (
      'Grocery Dashboard' => 'grocerydashboard.index|groceryajaxorder.index|groceryotdcharts.index',
    ),
    'Other Action' => 
    array (
      'Update Orders' => 'multiple-tracking-id.index|multiple-tracking-id.update',
      'Search Order' => 'search-multiple-tracking.index|searchorder.show|update-order.update',
      'Upload Image' => 'sprint-image-upload',
      'Manual Status History' => 'manual-status.index|manual-status.data',
      'Generate Csv' => 'generate-csv',
    ),
    'DNR Reporting' => 
    array (
      'DNR Reporting' => 'dnr.index|dnr.data',
      'DNR Excel' => 'dnr.export',
    ),
    'Customer Support' => 
    array (
      'Customer Support' => 'order-confirmation-list.index|orderConfirmation.transfer|Column.Update|add-notes',
      'History' => 'order-confirmation.history|add-notes',
      'Return To Merchant' => 'expired-order.history|return-order.update|add-notes',
      'Returned Order' => 'returned-order.index',
    ),
    'Reason' => 
    array (
      'Reason' => 'reason.index|reason.data',
      'Create' => 'reason.add|reason.create',
      'Edit' => 'reason.edit|reason.update',
      'Delete' => 'reason.delete',
    ),
  ),
  'routing_permissions' => 
  array (
    'Routing Permissions' => 
    array (
      'Sub Admin' => 'subadmins',
      'Montreal Routes' => 'montreal_routes',
      'Ottawa Routes' => 'ottawa_routes',
      'CTC Routes' => 'ctc_routes',
      'Flower Routes' => 'flower_routes',
      'WM Routes' => 'wm_routes',
      'Montreal Zones' => 'montreal_zones',
      'Ottawa Zones' => 'ottawa_zones',
      'CTC Zones' => 'ctc_zones',
      'Zone Types' => 'zone_types',
      'Montreal Amazon Failed Order' => 'amazon_failed_order',
      'Ottawa Amazon Failed Order' => 'amazon_failed_order_ottawa',
      'CTC Failed Order Toronto' => 'ctc_failed_order',
      'CTC Failed Order Ottawa' => 'ctc_failed_order_ottawa',
      'Reattempt Order' => 'reattempt_order',
      'Montreal Big Box Routes' => 'montreal_big_box_routes',
      'Ottawa Big Box Routes' => 'ottawa_big_box_routes',
      'CTC Big Box Routes' => 'ctc_big_box_routes',
      'Montreal Manifest Routing' => 'manifest_routes_montreal',
      'Ottawa Manifest Routing' => 'manifest_routes_ottawa',
      'Route Volume State' => 'route_volume_state',
      'Tracking Report' => 'tracking_report',
      'Montreal Manifest Report' => 'montreal_manifest_report',
      'Ottawa Manifest Report' => 'ottawa_manifest_report',
      'Other Action' => 'other_action',
      'Enable for routes' => 'enable_for_routes',
      'Return Portal' => 'return_portal',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
    'facebook' => 
    array (
      'client_id' => '227431116731085',
      'client_secret' => 'f2f4ebc8e8ecb4c40f741470b4ccbece',
      'redirect' => 'https://smrtesting.com/ksa/client/public/facebook/callback',
    ),
    'google' => 
    array (
      'client_id' => '48384254596-r8p3cno13r09ehceicnuihc668uhm45m.apps.googleusercontent.com',
      'client_secret' => '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',
      'redirect' => 'https://smrtesting.com/ksa/client/public/google/callback',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 525600,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
  ),
  'slack_group_permissions' => 
  array (
    'Slack Group Permissions' => 
    array (
      'CS E-Com Manager' => 'cs_e_com_manager',
      'CS E-Com Asst. Manager' => 'cs_e_com_asst_manager',
      'CS E-Com Supervisor' => 'cs_e_com_supervisor',
      'CS E-Com Team Lead' => 'cs_e_com_team_lead',
      'CS Rep E-com' => 'cs_rep_e_com',
      'CS E-Com Joey Support Team' => 'cs_e_com_joey_support_team',
      'CS E-Com Closing Team' => 'cs_e_com_closing_team',
      'Admin' => 'admin',
      'Hr' => 'hr',
    ),
  ),
  'statuscodes' => 
  array (
    'competed' => 
    array (
      'JCO_ORDER_DELIVERY_SUCCESS' => 17,
      'JCO_HAND_DELIEVERY' => 113,
      'JCO_DOOR_DELIVERY' => 114,
      'JCO_NEIGHBOUR_DELIVERY' => 116,
      'JCO_CONCIERGE_DELIVERY' => 117,
      'JCO_BACK_DOOR_DELIVERY' => 118,
      'JCO_OFFICE_CLOSED_DELIVERY' => 132,
      'JCO_DELIVER_GERRAGE' => 138,
      'JCO_DELIVER_FRONT_PORCH' => 139,
      'JCO_DEILVER_MAILROOM' => 144,
    ),
    'return' => 
    array (
      'Joey on the way to pickup' => 101,
      'Joey Incident' => 102,
      'Delay at pickup' => 103,
      'JCO_ITEM_DAMAGED_INCOMPLETE' => 104,
      'JCO_ITEM_DAMAGED_RETURN' => 105,
      'JCO_CUSTOMER_UNAVAILABLE_DELIEVERY_RETURNED' => 106,
      'JCO_CUSTOMER_UNAVAILABLE_LEFT_VOICE' => 107,
      'JCO_CUSTOMER_UNAVAILABLE_ADDRESS' => 108,
      'JCO_CUSTOMER_UNAVAILABLE_PHONE' => 109,
      'JCO_HUB_DELIEVER_REDELIEVERY' => 110,
      'JCO_HUB_DELIEVER_RETURN' => 111,
      'JCO_ORDER_REDELIVER' => 112,
      'JCO_ORDER_RETURN_TO_HUB' => 131,
      'JCO_CUSTOMER_REFUSED_DELIVERY' => 135,
      'CLIENT_REQUEST_CANCEL_ORDER' => 136,
      'DAMAGED_ROAD' => 143,
    ),
    'unattempted' => 
    array (
      'JCO_ORDER_NEW' => 13,
      'JCO_ORDER_SCHEDULED' => 61,
      'JCO_ORDER_AT_HUB_PROCESSING' => 124,
    ),
    'sort' => 
    array (
      'JCO_PACKAGES_SORT' => 133,
    ),
    'pickup' => 
    array (
      'JCO_HUB_PICKUP' => 121,
    ),
    'delay' => 
    array (
      'MARK_DELAY' => 255,
    ),
  ),
  'universal_slack_permissions' => 
  array (
    'FreshDesk Permissions' => 
    array (
      'Call Centre' => 'view_tickets',
      'Closing Team' => 'respond_tickets',
      'Ticket Support' => 'assign_tickets',
      'Live Orders' => 'modify_ticket_properties',
      'MTL Scheduling' => 'generate_reports',
      'OTT Scheduling' => 'automatic_ticket_assignment',
      'GTA Scheduling' => 'edit_configurations',
      'OPS Finance Team' => 'ops_finance_team',
      'E-Com Scheduling' => 'billing',
      'Joey Escalation' => 'joey_escalation',
      'Joeycofin Hr' => 'joeycofin_hr',
      'Claims' => 'claims',
      'Grocery Operations ' => 'grocery_operations ',
      'Grocery Tech ' => 'grocery_tech ',
      'Protein Chef ' => 'protein_chef ',
      'Scheduling Department ' => 'scheduling_department ',
      'Grocery Gateway ' => 'grocery_gateway ',
      'GTA Joey Training ' => 'gta_joey_training ',
      'General' => 'general',
      'MTL-CS-Hub' => 'mtl_cs_hub',
      'OTT-CS-Hub' => 'ott_cs_hub',
      'TOR-CS-Hub' => 'tor_cs_hub',
      'Newsletter' => 'news_letter',
      'Scheduling- Recruitment ' => 'scheduling_recruitment',
      'Joey Recrutiment ' => 'joey_recrutiment',
      'Admin & Hr' => 'admin_&_hr',
      'Finance Portal Queries' => 'finance_portal_queries',
      'Audit Team' => 'audit_team',
      'Hr Pk' => 'hr_pk',
      'Int Joeyco Tech Group' => 'int_joeyco_tech_group',
      'Joey App' => 'joeyapp',
      'Loblaws Joeyco Growth' => 'loblaws_joeyco_growth',
      'Tech Montreal' => 'tech_montreal',
      'MTL Manager Hub' => 'mtl_manager_hub',
      'Routing Montreal' => 'routing_montreal',
      'OTT Manager Hub' => 'ott_manager_hub',
      'Ottawa Finance Dpt' => 'ottawa_finance_dpt',
      'Routing Ottawa' => 'routing_ottawa',
      'Tech Ottawa' => 'tech_ottawa',
      'Tech Toronto' => 'tech_toronto',
      'TOR Manager Hub' => 'tor_manager_hub',
      'Toronto FinanceDpt' => 'toronto_finance_dpt',
      'Toronto Futbound Freshdesk' => 'toronto_outbound_freshdesk',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\resources\\views',
    ),
    'compiled' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\storage\\framework\\views',
  ),
  'jwt' => 
  array (
    'secret' => 'changeme',
    'ttl' => 60,
    'refresh_ttl' => 20160,
    'algo' => 'HS256',
    'user' => 'App\\User',
    'identifier' => 'id',
    'required_claims' => 
    array (
      0 => 'iss',
      1 => 'iat',
      2 => 'exp',
      3 => 'nbf',
      4 => 'sub',
      5 => 'jti',
    ),
    'blacklist_enabled' => true,
    'providers' => 
    array (
      'user' => 'Tymon\\JWTAuth\\Providers\\User\\EloquentUserAdapter',
      'jwt' => 'Tymon\\JWTAuth\\Providers\\JWT\\NamshiAdapter',
      'auth' => 'Tymon\\JWTAuth\\Providers\\Auth\\IlluminateAuthAdapter',
      'storage' => 'Tymon\\JWTAuth\\Providers\\Storage\\IlluminateCacheAdapter',
    ),
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\storage\\cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\storage\\exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => 'D:\\xampp-7-2-33\\htdocs\\live\\al-rafeeq\\client\\vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'slug_whitelist' => '._',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
