#include<bits/stdc++.h>
using namespace std;

int main(){
    freopen("input.txt","r",stdin);
    int n;
    cin>>n;
    int x,y,z;
    int cnt=0;
    for(int i=0;i<n;i++){
        cin>>x>>y>>z;
        if(x+y+z>=2){
            cnt++;
        }
    }
    cout<<cnt<<endl;
    return 0;
}
