############################################
# Setup Server
############################################

set :stage, :staging
set :stage_url, "http://staging.dailytimes.co.za"
server "54.68.244.159", user: "ubuntu", roles: %w{web app db}

set :pty, true

set :ssh_options, {
  forward_agent: true,
  auth_methods: ["publickey"],
  keys: ["/home/clive/Desktop/.ssh/NewsAfricaTODAY.pem"]
}

set :deploy_to, "/var/www/html/staging.dailytimes.co.za"

############################################
# Setup Git
############################################

set :branch, "staging"

############################################
# Extra Settings
############################################

#specify extra ssh options:

#set :ssh_options, {
#    auth_methods: %w(password),
#    password: 'password',
#    user: 'username',
#}

#specify a specific temp dir if user is jailed to home
#set :tmp_dir, "/path/to/custom/tmp"
