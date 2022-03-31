pipeline {
  agent any
  stages {
    stage('Build') {
      agent any
      steps {
        sh 'php --version'
        sh 'composer --version'
        sh 'touch .env'
        sh 'echo DATABASE_URL="mysql://root:greatpwdroot@host.docker.internal:3306/rest_api?serverVersion=5.7&charset=utf8mb4" >> .env'
        sh 'echo APP_ENV=dev >> .env'
        sh 'composer install --prefer-dist --no-interaction'
        sh 'bin/console secrets:set APP_SECRET --random'
      }
    }
    stage('PHP Code Sniffer') {
      agent any
      steps {
        sh 'vendor/bin/phpcs src/Service'
      }
    }
    stage('Unit Test') {
      agent any
      steps {
        sh 'vendor/bin/phpunit --coverage-clover=\'reports/coverage/coverage.xml\''
      }
    }
  }
}
