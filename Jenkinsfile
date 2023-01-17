pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                git url: 'https://github.com/TheoSigaud/challenge.git'
            }
        }
        stage('Build Vue.js App') {
            steps {
                sh 'npm install'
                sh 'npm run build'
            }
        }
        stage('Test Vue.js App') {
            steps {
                sh 'npm run test'
            }
        }
        stage('Build API Platform') {
            steps {
                sh 'composer install'
                sh 'php bin/console doctrine:schema:update --force'
            }
        }
        stage('Test API Platform') {
            steps {
                sh './vendor/bin/phpunit'
            }
        }
        // stage('Deploy to dev') {
        //     steps {
        //         sh 'rsync -av --delete dist/ user@dev.example.com:/var/www/vue-app'
        //         sh 'rsync -av --delete api/ user@dev.example.com:/var/www/api-platform'
        //     }
        // }
        // stage('Deploy to staging') {
        //     steps {
        //         sh 'rsync -av --delete dist/ user@staging.example.com:/var/www/vue-app'
        //         sh 'rsync -av --delete api/ user@staging.example.com:/var/www/api-platform'
        //     }
        // }
        // stage('Deploy to production') {
        //     steps {
        //         sh 'rsync -av --delete dist/ user@example.com:/var/www/vue-app'
        //         sh 'rsync -av --delete api/ user@example.com:/var/www/api-platform'
        //     }
        // }
    }
}