# Use an official Alpine Linux image as the base image
FROM alpine:3.12

# Set the working directory to /app
WORKDIR /app

# Define the command to run when the container starts
CMD [ "echo", "Hello, World!" ]
