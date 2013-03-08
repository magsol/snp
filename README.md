# Social Network Portal

This was the final project for fall 2009's rendition of 15-637 at Carnegie Mellon. The project was to implement a portal from which users could unify and access their social networking accounts.

# Description

This was a project on which I worked with Arpit Tandon. He devised the original idea to design a portal from which a user could administrate all their social networking accounts, negating the need to log into each and every one separately to administrate each one (similar to power.com). Obviously, implementing the full functionality of each social networking site was beyond the scope of what we could accomplish in the specified time frame, so we focused instead on "read"ing each site we integrated.

We began with the mission of integrating OpenSocial sites, but this soon evolved into integrating sites which supported OAuth. Using a single OAuth library, we could implement 3-legged authentication with each site, enabling authentication with each site which simultaneously preventing the need to locally store users' login credentials for each site.

As of this release, the supported social networks are Twitter, MySpace, and LinkedIn. Twitter is the only network which allows posting; all others are read-only.

Here's a live demo: http://www.magsol.me/final637/
