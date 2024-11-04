# Jesker
This is a PHP written tool to fake rcon responses from the game server.
Used for testing applications relying on game server communication.

## Supported Protocols
- Rust Source-RCON

## TBD
- Rust Web-RCON
- API to influence reponses while it is running, like changing players, chat messages, console messages

## API
- Fake responses can be injected using the following API:
- TBD

# Example usage

Here the rcon server is being used to emulate 3 servers with a bunch of players on them and is being used in [RustDroid](https://play.google.com/store/apps/details?id=de.mbdesigns.rustdroid)

![rustdroid example](https://raw.githubusercontent.com/atomy/jesker/refs/heads/main/docs/rustdroid-example.png)