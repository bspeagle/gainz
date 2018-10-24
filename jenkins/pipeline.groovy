node {
    stage("Clone repo and copy to app server.") {
        sh 'rm -r -f gainz'
        withCredentials([usernamePassword(credentialsId: 'github', passwordVariable: 'PASSWORD', usernameVariable: 'USERNAME')]) {
            sh 'git clone https://$USERNAME:$PASSWORD@github.com/bspeagle/gainz.git'
        }
        
        configFileProvider([configFile(fileId: 'bspeagle_PEM', targetLocation: '.')]) {
            configFileProvider([configFile(fileId: 'gainz_ENV', targetLocation: '.')]) {
                sh 'mv g.env .env'
            }
            sh 'sudo chmod 600 bspeagle.pem'
            sh 'scp -i bspeagle.pem -r gainz ec2-user@34.201.20.194:/home/ec2-user'
            sh 'rm bspeagle.pem'
        }
    }
    stage("SSH t-t-time!") {
        sshagent (credentials: ['aws-ec2-user']) {
            withCredentials([string(credentialsId: 'appServer', variable: 'IP')]) {
                sh 'ssh -o StrictHostKeyChecking=no -l ec2-user $IP "cd gainz; composer install --no-interaction; cd ..; sudo rsync -av --progress gainz /var/www/html --exclude jenkins --exclude .git --exclude .vscode; rm -r gainz"'
            }
        }
    }
}