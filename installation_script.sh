#!/bin/bash
# Update system
sudo apt update -y

# Install Java (Required for Jenkins & SonarQube)
sudo touch /etc/apt/keyrings/adoptium.asc
sudo wget -O /etc/apt/keyrings/adoptium.asc https://packages.adoptium.net/artifactory/api/gpg/key/public
echo "deb [signed-by=/etc/apt/keyrings/adoptium.asc] https://packages.adoptium.net/artifactory/deb $(awk -F= '/^VERSION_CODENAME/{print$2}' /etc/os-release) main" | sudo tee /etc/apt/sources.list.d/adoptium.list
sudo apt update -y
sudo apt install temurin-17-jdk -y
/usr/bin/java --version

# Install Jenkins on Port 8082
curl -fsSL https://pkg.jenkins.io/debian-stable/jenkins.io-2023.key | sudo tee /usr/share/keyrings/jenkins-keyring.asc > /dev/null
echo deb [signed-by=/usr/share/keyrings/jenkins-keyring.asc] https://pkg.jenkins.io/debian-stable binary/ | sudo tee /etc/apt/sources.list.d/jenkins.list > /dev/null
sudo apt update -y
sudo apt install jenkins -y
sudo sed -i 's/HTTP_PORT=8080/HTTP_PORT=8082/' /etc/default/jenkins
sudo systemctl restart jenkins
sudo systemctl enable jenkins
sudo cat /var/lib/jenkins/secrets/initialAdminPassword

# Install Docker
sudo apt install docker.io -y
sudo usermod -aG docker ubuntu
newgrp docker
sudo chmod 777 /var/run/docker.sock
docker version

# Install Trivy (Security Scanner)
sudo apt-get install wget apt-transport-https gnupg lsb-release -y
wget -qO - https://aquasecurity.github.io/trivy-repo/deb/public.key | gpg --dearmor | sudo tee /usr/share/keyrings/trivy.gpg > /dev/null
echo "deb [signed-by=/usr/share/keyrings/trivy.gpg] https://aquasecurity.github.io/trivy-repo/deb $(lsb_release -sc) main" | sudo tee -a /etc/apt/sources.list.d/trivy.list
sudo apt update
sudo apt install trivy -y

# Install Terraform
wget -O- https://apt.releases.hashicorp.com/gpg | sudo gpg --dearmor -o /usr/share/keyrings/hashicorp-archive-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/hashicorp-archive-keyring.gpg] https://apt.releases.hashicorp.com $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/hashicorp.list
sudo apt update && sudo apt install terraform -y

# Install kubectl
sudo apt update
sudo apt install curl -y
curl -LO https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl
sudo install -o root -g root -m 0755 kubectl /usr/local/bin/kubectl
kubectl version --client

# Install Ansible
sudo apt update
sudo apt install ansible -y
ansible --version

# Install SonarQube on Port 9001
sudo apt update && sudo apt install unzip -y
sudo mkdir -p /opt/sonarqube
cd /opt/sonarqube
wget https://binaries.sonarsource.com/Distribution/sonarqube/sonarqube-10.2.0.77628.zip
sudo unzip sonarqube-10.2.0.77628.zip
sudo mv sonarqube-10.2.0.77628 sonarqube
sudo adduser --system --no-create-home --group sonar
sudo chown -R sonar:sonar /opt/sonarqube
sudo sed -i 's/#sonar.web.port=9000/sonar.web.port=9001/' /opt/sonarqube/sonarqube/conf/sonar.properties
sudo bash -c 'cat <<EOF > /etc/systemd/system/sonarqube.service
[Unit]
Description=SonarQube service
After=syslog.target network.target

[Service]
Type=forking
ExecStart=/opt/sonarqube/sonarqube/bin/linux-x86-64/sonar.sh start
ExecStop=/opt/sonarqube/sonarqube/bin/linux-x86-64/sonar.sh stop
User=sonar
Group=sonar
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF'

sudo systemctl enable sonarqube
sudo systemctl start sonarqube

# Install Grafana on Port 3001
sudo apt-get install -y software-properties-common
sudo add-apt-repository "deb https://packages.grafana.com/oss/deb stable main"
wget -q -O - https://packages.grafana.com/gpg.key | sudo apt-key add -
sudo apt-get update && sudo apt-get install grafana -y
sudo sed -i 's/^;http_port = 3000/http_port = 3001/' /etc/grafana/grafana.ini
sudo systemctl restart grafana-server
sudo systemctl enable grafana-server

# Display Service Access Information
echo "Installation completed. Access services at:
Jenkins: http://<your-vm-ip>:8082
SonarQube: http://<your-vm-ip>:9001
Grafana: http://<your-vm-ip>:3001"
