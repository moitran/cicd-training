pipeline {
  agent any
  stages {
    stage('Build') {
      agent any
      steps {
        sh 'php --version'
        sh 'composer --version'
        sh 'composer install --prefer-dist --no-interaction'
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
