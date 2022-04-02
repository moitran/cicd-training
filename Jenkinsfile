pipeline {
    agent any

    environment {
        APP_ENV = 'dev'
        DATABASE_URL = credentials("superheros-dev-db-url")
        SUPERHEROS_API_CODECOV_TOKEN = credentials("superheros-api-codecov-token")
        DOCKER_TAG="${GIT_COMMIT.substring(0,7)}"
        DOCKER_IMAGE = 'moitran/cicd-training'
        PR_STATE = "${GITHUB_PR_STATE}"
        GIT_BRANCH = "${GIT_BRANCH}"
    }

    stages {
        stage('Build Code') {
            steps {
                sh "echo ${PR_STATE}"
                sh "echo ${GIT_BRANCH}"
                sh 'php --version'
                sh 'composer --version'
                sh 'rm .env && touch .env'
                sh 'echo APP_ENV=${APP_ENV} >> .env'
                sh 'echo DATABASE_URL=${DATABASE_URL} >> .env'
                sh 'composer install --prefer-dist --no-interaction'
                sh 'bin/console secrets:set APP_SECRET --random'
            }
        }

        stage('PHP Code Sniffer') {
            steps {
                sh 'vendor/bin/phpcs src/Service --standard=PSR2'
            }
        }

        stage('Unit Test') {
            steps {
                sh './bin/phpunit --coverage-clover coverage.xml'
                sh 'curl -s https://codecov.io/bash | bash -s - -t ${SUPERHEROS_API_CODECOV_TOKEN}'
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        sh 'docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} -f Dockerfile-deploy .'
                        sh 'docker tag ${DOCKER_IMAGE}:${DOCKER_TAG} ${DOCKER_IMAGE}:latest'
                    }
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        withCredentials([usernamePassword(credentialsId: 'docker-hub', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                            sh "echo $DOCKER_PASSWORD | docker login --username $DOCKER_USERNAME --password-stdin"
                            sh "docker push ${DOCKER_IMAGE}:${DOCKER_TAG}"
                            sh "docker push ${DOCKER_IMAGE}:latest"

                        }
                        //clean to save disk
                        sh '''
                            docker rmi -f $(docker images ${DOCKER_IMAGE} -a -q)
                            docker image prune -a -f
                        '''
                    }
                }
            }
        }

        stage('Deploy on PROD') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        sshagent(credentials: ['ssh-ec2-id']) {
                            sh '''
                                ssh -o StrictHostKeyChecking=no ubuntu@ec2-3-0-92-247.ap-southeast-1.compute.amazonaws.com << EOF
                                docker pull ${DOCKER_IMAGE}
                                docker stop superheroes-api || true
                                docker rm superheroes-api || true
                                docker image prune -a -f
                                docker container prune -f
                                docker run -d -p 80:80 --name superheroes-api --add-host host.docker.internal:host-gateway ${DOCKER_IMAGE}:latest
                                exit
                            EOF'''
                        }
                    }
                }
            }
        }
    }

    post {
        success {
            echo "SUCCESSFUL"
        }

        failure {
            echo "FAILED"
        }
    }
}
