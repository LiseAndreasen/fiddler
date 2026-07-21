# Program to plot 2-D Heat map
# using matplotlib.pyplot.imshow() method
import numpy as np
import matplotlib.pyplot as plt
from worldcup_data2 import data2, s_min, s_max, t_min, t_max

plt.imshow(data2, cmap='YlGnBu', extent=[t_min,t_max,s_max,s_min])
plt.colorbar()
plt.show()

min_data = 0.2775
for i in range(len(data2)):
	for j in range(len(data2[0])):
		if data2[i][j] < min_data:
			data2[i][j] = min_data

plt.imshow(data2, cmap='YlGnBu', extent=[t_min,t_max,s_max,s_min])
plt.colorbar()
plt.show()