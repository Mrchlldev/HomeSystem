# HomeSystem
A PocketMine-MP API 5.0.0 Plugin

# Command
<ul>
  <li>/home (home name)</li>
  <li>/sethome (home name)</li>
  <li>/getmyhome</li>
</ul>

# Config
```yaml
prefix: "§7[§aHome§2System§7]§r"

## Blacklist world, player can't set home in blacklisted world, you can add more world folder name here!
blacklist-world: ["world1", "world2", "world3"]

## Home limit per permission, you can add more permission here!
home_limits:
  default: 3
  homesystem.limit.vip: 5
  homesystem.limit.premium: 10
```
